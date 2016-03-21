<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once("secretary.php");
require_once("course.php");
require_once("program.php");
require_once("phase.php");

require_once(APPPATH."/constants/PermissionConstants.php");
require_once(APPPATH."/constants/SelectionProcessConstants.php");
require_once(APPPATH."/exception/SelectionProcessException.php");
require_once(APPPATH."/data_types/selection_process/SelectionProcess.php");
require_once(APPPATH."/data_types/selection_process/RegularStudentProcess.php");
require_once(APPPATH."/data_types/selection_process/SpecialStudentProcess.php");
require_once(APPPATH."/data_types/selection_process/ProcessSettings.php");

class SelectiveProcess extends CI_Controller {

    const MODEL_NAME = "selectiveprocess_model";
    const MODEL_OBJECT = "process_model";

    public function __construct(){
        parent::__construct();

        $this->load->model(self::MODEL_NAME, self::MODEL_OBJECT);
    }

    public function index() {
        
        $secretary = new Secretary();

        $programsAndCourses = $secretary->getSecretaryPrograms();
        $programs = $programsAndCourses['programs'];

        $data = array(
            'programs' => $programs
        );

        loadTemplateSafelyByPermission(PermissionConstants::SELECTION_PROCESS_PERMISSION, "selection_process/index", $data);
    }

    public function programCourses($programId){

        $session = $this->session->userdata("current_user");
        $userData = $session['user'];
        $secretaryId = $userData['id'];

        $courseController = new Course();
        $secretaryCourses = $courseController->getCoursesOfSecretary($secretaryId);

        $program = new Program();
        $programCourses = $program->getProgramCourses($programId);

        $courses = array();
        foreach($secretaryCourses as $secretaryCourse){
            
            foreach($programCourses as $programCourse){
                
                if($secretaryCourse == $programCourse){
                    $courses[] = $programCourse;
                }
            }
        }

        $data = array(
            'courses' => $courses
        );

        loadTemplateSafelyByPermission(PermissionConstants::SELECTION_PROCESS_PERMISSION, "selection_process/program_courses", $data);
    }

    public function openSelectiveProcess($courseId){

        $course = new Course();
        $course = $course->getCourseById($courseId);

        $phase = new Phase();
        $phases = $phase->getAllPhases(); 

        $data = array(
            'course' => $course,
            'phases' => $phases
        );

        loadTemplateSafelyByPermission(PermissionConstants::SELECTION_PROCESS_PERMISSION, "selection_process/new", $data);
    }

    private function setUploadOptions(){
        
        $config['upload_path'] = APPPATH.'/upload_files/notices/';
        //$config['file_name'] = "edital";
        $config['allowed_types'] = 'pdf';
        $config['max_size'] = '5500';
        $config['remove_spaces'] = TRUE;

        return $config;
    }

    public function newSelectionProcess(){

        $courseId = $this->input->post("course");
        $studentType = $this->input->post("student_type");
        $noticeName = $this->input->post("selective_process_name");
        $startDate = $this->input->post("selective_process_start_date");
        $endDate = $this->input->post("selective_process_end_date");


        $config = $this->setUploadOptions(); 

        // Remember to give the proper permission to the /upload_files folder
        $this->load->library('upload', $config);

        if($this->upload->do_upload("notice_file")){
            
            $noticeFile = $this->upload->data();
            $noticePath = $noticeFile['full_path'];

            try{
                switch($studentType){
                    case SelectionProcessConstants::REGULAR_STUDENT:
                        $process = new RegularStudentProcess($courseId, $noticeName);
                        break;
                    
                    case SelectionProcessConstants::SPECIAL_STUDENT:
                        $process = new SpecialStudentProcess($courseId, $noticeName);
                        break;

                    default:
                        $process = FALSE;
                        break;
                }

                if($process !== FALSE){
                    
                    // Adds the path to the selection process
                    $process->setNoticePath($noticePath);

                    $preProject = $this->input->post("phase_".SelectionProcessConstants::PRE_PROJECT_EVALUATION_PHASE_ID);
                    
                    $preProjectWeight = $this->input->post("phase_weight_".SelectionProcessConstants::PRE_PROJECT_EVALUATION_PHASE_ID);

                    $writtenTest = $this->input->post("phase_".SelectionProcessConstants::WRITTEN_TEST_PHASE_ID);
                    
                    $writtenTestWeight = $this->input->post("phase_weight_".SelectionProcessConstants::WRITTEN_TEST_PHASE_ID);

                    $oralTest = $this->input->post("phase_".SelectionProcessConstants::ORAL_TEST_PHASE_ID);

                    $oralTestWeight = $this->input->post("phase_weight_".SelectionProcessConstants::ORAL_TEST_PHASE_ID);

                    $phases = array();
                    
                    if($preProject !== FALSE){
                        $preProject = new PreProjectEvaluation($preProjectWeight, FALSE, $preProject);
                        $phases[] = $preProject;
                    }

                    if($writtenTest !== FALSE){
                        $writtenTest = new WrittenTest($writtenTestWeight, FALSE, $writtenTest);
                        $phases[] = $writtenTest;
                    }

                    if($oralTest !== FALSE){
                        $oralTest = new OralTest($oralTestWeight, FALSE, $oralTest);
                        $phases[] = $oralTest;
                    }

                    if(!empty($phases)){
                        
                        // All processes have homologation
                        $phases[] = new Homologation();

                        // Just to test
                            

                            //FIX HERE

                            $phasesOrder = array(1,2,3,4);

                        //

                        $processSettings = new ProcessSettings($startDate, $endDate, $phases, $phasesOrder);

                        $process->addSettings($processSettings);

                        // Finally saves the selection process
                        $this->process_model->save($process);

                    }else{
                        // The process must have at least one phase
                    }

                }else{
                    // Invalid Student Type
                }
            }catch(SelectionProcessException $e){
                var_dump($e->getMessage());
                exit;
            }

        }else{
            // Errors on file upload
            $errors = $this->upload->display_errors();
        }
    }

}
