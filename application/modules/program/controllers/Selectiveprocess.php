<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(MODULESPATH."/auth/constants/PermissionConstants.php");
require_once(MODULESPATH."/program/constants/SelectionProcessConstants.php");
require_once(MODULESPATH."/program/exception/SelectionProcessException.php");
require_once(MODULESPATH."/program/domain/selection_process/SelectionProcess.php");
require_once(MODULESPATH."/program/domain/selection_process/RegularStudentProcess.php");
require_once(MODULESPATH."/program/domain/selection_process/SpecialStudentProcess.php");
require_once(MODULESPATH."/program/domain/selection_process/ProcessSettings.php");

class SelectiveProcess extends MX_Controller {

    const MODEL_NAME = "program/selectiveprocess_model";
    const MODEL_OBJECT = "process_model";

    public function __construct(){
        parent::__construct();

        $this->load->model(self::MODEL_NAME, self::MODEL_OBJECT);
    }

    public function index() {
        
        $this->load->module("secretary/secretary");
        $programsAndCourses = $this->secretary->getSecretaryPrograms();
        $programs = $programsAndCourses['programs'];

        $data = array(
            'programs' => $programs
        );

        loadTemplateSafelyByPermission(PermissionConstants::SELECTION_PROCESS_PERMISSION, "program/selection_process/index", $data);
    }

    public function programCourses($programId){

        $session = getSession();
        $user = $session->getUserData();
        $secretaryId = $user->getId();

        $this->load->model("program/course_model");
        $secretaryCourses = $this->course_model->getCoursesOfSecretary($secretaryId);

        $this->load->model("program/program_model");
        $programCourses = $this->program_model->getProgramCourses($programId);

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

        loadTemplateSafelyByPermission(PermissionConstants::SELECTION_PROCESS_PERMISSION, "program/selection_process/program_courses", $data);
    }

    public function courseSelectiveProcesses($courseId){

        $this->load->model("program/course_model");
        $course = $this->course_model->getCourseById($courseId);

        $selectiveProcesses = $this->getCourseSelectiveProcesses($courseId);

        $data = array(
            'course' => $course,
            'selectiveProcesses' => $selectiveProcesses
        );

        loadTemplateSafelyByPermission(PermissionConstants::SELECTION_PROCESS_PERMISSION, "program/selection_process/course_process", $data);
    }

    public function openSelectiveProcess($courseId){

        $this->load->model("program/course_model");
        $course = $this->course_model->getCourseById($courseId);

        $this->load->module("program/phase");
        $phases = $this->phase->getAllPhases(); 

        $data = array(
            'course' => $course,
            'phases' => $phases
        );

        loadTemplateSafelyByPermission(PermissionConstants::SELECTION_PROCESS_PERMISSION, "program/selection_process/new", $data);
    }

    private function setUploadOptions($fileName, $programId, $courseId, $processId){
        
        // Remember to give the proper permission to the /upload_files folder
        define("NOTICES_UPLOAD_FOLDER_PATH", "upload_files/notices");
        
        $desiredPath = APPPATH.NOTICES_UPLOAD_FOLDER_PATH;

        $ids = array(
            "p" => $programId,
            "c" => $courseId,
            "s" => $processId
        );

        $path = $this->createFolders($desiredPath, $ids);

        $config['upload_path'] = $path;
        $config['file_name'] = $fileName;
        $config['allowed_types'] = 'pdf';
        $config['max_size'] = '5500';
        $config['remove_spaces'] = TRUE;

        return $config;
    }

    private function createFolders($desiredPath, $ids){

        foreach ($ids as $folderType => $id) {
            
            $auxPath = $desiredPath;

            $pathToAdd = "/".$folderType."_".$id;

            if(is_dir($auxPath.$pathToAdd)){
                $desiredPath .= $pathToAdd;
                $auxPath = $desiredPath;
            }
            else{
                mkdir($auxPath.$pathToAdd, 0755, TRUE);
                $desiredPath .= $pathToAdd;
            }
        }

        return $desiredPath;
    }

    public function saveNoticeFile(){

        $this->load->library('upload');

        $courseId = $this->input->post("course");
        $processId = base64_decode($this->input->post("selection_process_id"));

        $process = $this->process_model->getById($processId);

        $this->load->model("program/course_model");
        $course = $this->course_model->getCourseById($courseId);
        
        $config = $this->setUploadOptions($process->getName(), $course["id_program"], $course["id_course"], $processId);
        
        $this->upload->initialize($config);

        if($this->upload->do_upload("notice_file")){

            $noticeFile = $this->upload->data();
            $noticePath = $noticeFile['full_path'];

            $wasUpdated = $this->updateNoticeFile($processId, $noticePath);

            if($wasUpdated){
                $status = "success";
                $message = "Processo Seletivo e edital salvo com sucesso!";
                $pathToRedirect = "program/selectiveprocess/courseSelectiveProcesses/{$courseId}";
            }else{
                $status = "danger";
                $message = "Não foi possível salvar o arquivo do Edital. Tente novamente.";
                $pathToRedirect = "program/selectiveprocess/tryUploadNoticeFile/{$processId}";
            }

        }else{
            // Errors on file upload
            $errors = $this->upload->display_errors();
            
            $status = "danger";
            $message = $errors."<br>Tente novamente.";
            $pathToRedirect = "program/selectiveprocess/tryUploadNoticeFile/{$processId}";
        }

        $this->session->set_flashdata($status, $message);
        redirect($pathToRedirect);
    }

    private function updateNoticeFile($processId, $noticePath){

        $wasUpdated = $this->process_model->updateNoticeFile($processId, $noticePath);

        return $wasUpdated;
    }

    public function tryUploadNoticeFile($processId){

        $process = $this->process_model->getById($processId);

        $data = array(
            'process' => $process
        );

        loadTemplateSafelyByPermission(PermissionConstants::SELECTION_PROCESS_PERMISSION, "program/selection_process/upload_notice", $data);
    }

    private function getCourseSelectiveProcesses($courseId){

        $processes = $this->process_model->getCourseSelectiveProcesses($courseId);


        if($processes !== FALSE){
 
            $selectiveProcesses = array();

            foreach($processes as $process){
                
                if($process[SelectiveProcess_model::PROCESS_TYPE_ATTR] === SelectionProcessConstants::REGULAR_STUDENT){

                    try{
                        
                        $selectionProcess = new RegularStudentProcess(
                            $process[SelectiveProcess_model::COURSE_ATTR],
                            $process[SelectiveProcess_model::NOTICE_NAME_ATTR],
                            $process[SelectiveProcess_model::ID_ATTR]
                        );

                    }catch(SelectionProcessException $e){
                        $selectionProcess = FALSE;
                    }

                }else{
                    try{
                        $selectionProcess = new SpecialStudentProcess(
                            $process[SelectiveProcess_model::COURSE_ATTR],
                            $process[SelectiveProcess_model::NOTICE_NAME_ATTR],
                            $process[SelectiveProcess_model::ID_ATTR]
                        );
                    }catch(SelectionProcessException $e){
                        $selectionProcess = FALSE;
                    }
                }

                if($selectionProcess !== FALSE){
                    $selectiveProcesses[] = $selectionProcess;                    
                }else{
                    // Something is wrong with the data registered on database
                    // Should not have wrong data because the data is validated before inserting, using the same class.
                    show_error("O banco de dados retornou um valor inválido da tabela ".SelectiveProcess_model::SELECTION_PROCESS_TABLE.". Contate o administrador.", 500, "Algo de errado com o banco de dados");
                }
            }

        }else{
            $selectiveProcesses = FALSE;
        }

        return $selectiveProcesses;
    }
}
