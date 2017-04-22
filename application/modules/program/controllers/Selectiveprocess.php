
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(MODULESPATH."/auth/constants/PermissionConstants.php");
require_once(MODULESPATH."/auth/constants/GroupConstants.php");
require_once(MODULESPATH."/program/constants/SelectionProcessConstants.php");
require_once(MODULESPATH."/program/exception/SelectionProcessException.php");
require_once(MODULESPATH."/program/domain/selection_process/SelectionProcess.php");
require_once(MODULESPATH."/program/domain/selection_process/RegularStudentProcess.php");
require_once(MODULESPATH."/program/domain/selection_process/SpecialStudentProcess.php");
require_once(MODULESPATH."/program/domain/selection_process/ProcessSettings.php");

require_once(MODULESPATH."/program/domain/selection_process/phases/Homologation.php");
require_once(MODULESPATH."/program/domain/selection_process/phases/PreProjectEvaluation.php");
require_once(MODULESPATH."/program/domain/selection_process/phases/WrittenTest.php");
require_once(MODULESPATH."/program/domain/selection_process/phases/OralTest.php");

class SelectiveProcess extends MX_Controller {

    const MODEL_NAME = "program/selectiveprocess_model";
    const MODEL_OBJECT = "process_model";

    public function __construct(){
        parent::__construct();

        $this->load->model(self::MODEL_NAME, self::MODEL_OBJECT);
        $this->load->helper('selectionprocess');
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
        $configData = $this->getConfigDataOfProcesses($selectiveProcesses);
        $status = $this->getProcessStatus($selectiveProcesses);

        $data = array(
            'course' => $course,
            'selectiveProcesses' => $selectiveProcesses
        );

        $data = $data + $configData + $status;
        
        loadTemplateSafelyByPermission(PermissionConstants::SELECTION_PROCESS_PERMISSION, "program/selection_process/course_process", $data);
    }

    private function getConfigDataOfProcesses($selectiveProcesses){
        
        $processesTeachers = array();
        $processesDocs = array();
        $processesResearchLines = array();

        $this->load->model('program/selectiveprocessconfig_model', 'process_config_model');

        if($selectiveProcesses !== FALSE){
            foreach ($selectiveProcesses as $selectiveProcess) {
                $processId = $selectiveProcess->getId();
                $processTeachers = $this->process_model->getProcessTeachers($processId);
                $processDocs = $this->process_config_model->getProcessDocs($processId);
                $courseResearchLines = $this->course_model->getCourseResearchLines($selectiveProcess->getCourse());

                $processesTeachers[$processId] = ($processTeachers);
                $processesDocs[$processId] = ($processDocs);
                $processesResearchLines[$processId] = ($courseResearchLines);
            }
        }

        $data = array(
            'processesTeachers' => $processesTeachers,
            'processesDocs' => $processesDocs,
            'processesResearchLines' => $processesResearchLines
        );

        return $data;
    } 

    private function getProcessStatus($selectiveProcesses){

        $status = array();
        $configStatusNotices = array();
        if($selectiveProcesses !== FALSE){
            foreach ($selectiveProcesses as $selectiveProcess) {
                $selectiveProcessId = $selectiveProcess->getId();
                $noticePath = $selectiveProcess->getNoticePath();
                if(!is_null($noticePath)){
                    $status[$selectiveProcessId] = "<span class='label label-success'>".SelectionProcessConstants::DISCLOSED."</span>";
                }
                else{
                    $status[$selectiveProcessId] = "<span class='label label-warning'>".SelectionProcessConstants::NOT_DISCLOSED."</span>";
                }
                $settings = $selectiveProcess->getSettings();
                $noticeWithAllConfig = $settings->getDatesDefined() && $settings->getNeededDocsSelected() && $settings->getTeachersSelected();
                $configStatusNotices[$selectiveProcessId] = $noticeWithAllConfig;
                if(!$noticeWithAllConfig){
                    $status[$selectiveProcessId] .= "<br><span class='label label-danger'>".SelectionProcessConstants::INCOMPLETE_CONFIG."</span>";
                }
            }
        }

        $data = array(
            'status' => $status,
            'noticeWithAllConfig' => $configStatusNotices
        );

        return $data;
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

    
    private function getCourseSelectiveProcesses($courseId){

        $processes = $this->process_model->getCourseSelectiveProcesses($courseId);

        if($processes !== FALSE){

            $selectiveProcesses = array();

            foreach($processes as $process){

                $phasesOrder = unserialize($process[SelectiveProcess_model::PHASE_ORDER_ATTR]);
                $startDate = convertDateTimeToDateBR($process[SelectiveProcess_model::START_DATE_ATTR]);
                $endDate = convertDateTimeToDateBR($process[SelectiveProcess_model::END_DATE_ATTR]);
                $phases = $this->process_model->getPhases($process['id_process']);
                $phases = $this->process_model->sortPhasesBasedInOrder($phases, $phasesOrder);
                $settings = new ProcessSettings(
                    $startDate,
                    $endDate,
                    $phases,
                    $phasesOrder,
                    $process['dates_defined'],
                    $process['needed_docs_selected'],
                    $process['teachers_selected']
                );
                if($process[SelectiveProcess_model::PROCESS_TYPE_ATTR] === SelectionProcessConstants::REGULAR_STUDENT){
                    try{
                        $selectionProcess = new RegularStudentProcess(
                            $process[SelectiveProcess_model::COURSE_ATTR],
                            $process[SelectiveProcess_model::NOTICE_NAME_ATTR],
                            $process[SelectiveProcess_model::ID_ATTR]
                        );
                        $selectionProcess->addSettings($settings);
                        $noticePath = $process[SelectiveProcess_model::NOTICE_PATH_ATTR];
                        if(!is_null($noticePath)){
                            $selectionProcess->setNoticePath($noticePath);
                        }

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
                        $selectionProcess->addSettings($settings);
                        $noticePath = $process[SelectiveProcess_model::NOTICE_PATH_ATTR];
                        if(!is_null($noticePath)){
                            $selectionProcess->setNoticePath($noticePath);
                        }
                    }catch(SelectionProcessException $e){
                        $selectionProcess = FALSE;
                    }
                }

                if($selectionProcess !== FALSE){
                    $selectiveProcesses[] = $selectionProcess;
                }else{
                    // Something is wrong with the data registered on database
                    // Should not have wrong data because the data is validated before inserting, using the same class.
                    show_error("O banco de dados retornou um valor inválido da tabela ".$this->process_model->TABLE.". Contate o administrador.", 500, "Algo de errado com o banco de dados");
                }
            }

        }else{
            $selectiveProcesses = FALSE;
        }
        return $selectiveProcesses;
    }

    private function getEditProcessViewData($processId){
        $selectiveProcess = $this->process_model->getById($processId);
        $this->load->module("program/phase");
        $allPhases = $this->phase->getAllPhases();

        $phases = $this->getProcessPhasesToEdit($selectiveProcess, $allPhases);

        $noticePath = $selectiveProcess->getNoticePath();
        $names = explode("/", $noticePath);
        $noticeFileName = array_pop($names);

        $editProcessData = array(
            'process' => $selectiveProcess,
            'processId' => $processId,
            'phasesNames' => $phases['phasesNames'],
            'phasesWeights' => $phases['phasesWeights'],
            'phasesGrades' => $phases['phasesGrades'],
            'noticeFileName' => $noticeFileName,
            'allPhases' => $allPhases
        );

        return $editProcessData;
    }

    public function edit($processId){

        $editProcessData = $this->getEditProcessViewData($processId);

        $this->load->model("program/course_model");
        $process = $editProcessData['process'];
        $course = $this->course_model->getCourseById($process->getCourse());
        $this->load->module("program/selectiveprocessconfig");
        $defineTeacherData = $this->selectiveprocessconfig->getDefineTeachersViewData($processId, $course['id_program']);
        $configData = $this->selectiveprocessconfig->getDataSubscriptionConfig($processId);
        $data = $editProcessData + $defineTeacherData + $configData;
        $data['programId'] = $course['id_program'];
        $data['phasesIds'] = $this->selectiveprocessconfig->getPhasesIds($data['process']);


        loadTemplateSafelyByPermission(PermissionConstants::SELECTION_PROCESS_PERMISSION, "program/selection_process/edit", $data);
    }

    private function getProcessPhasesToEdit($selectiveProcess, $allPhases){

        $phasesNames = array();
        $phasesWeights = array();
        $phasesGrades = array();
        $processPhases = $selectiveProcess->getSettings()->getPhases();

        foreach ($allPhases as $phase) {
            $hasThePhase = FALSE;
            $phaseId = $phase->getPhaseId();
            if(!empty($processPhases)){
                foreach ($processPhases as $processPhase) {
                    $processPhaseId = $processPhase->getPhaseId();
                    if($phaseId == $processPhaseId){
                        $phasesNames[$phaseId] = $processPhase->getPhaseName();
                        if($phaseId != SelectionProcessConstants::HOMOLOGATION_PHASE_ID){
                            $phasesWeights[$phaseId] = $processPhase->getWeight();
                            $phasesGrades[$phaseId] = $processPhase->getGrade();
                        }
                        else{
                            $phasesWeights[$phaseId] = "0";
                            $phasesGrades[$phaseId] = "0";
                        }
                        $hasThePhase = TRUE;
                        break;
                    }
                }
            }
            if(!$hasThePhase){
                $phasesNames[$phaseId] = $phase->getPhaseName();
                $phasesWeights[$phaseId] = "-1"; // Phase Not selected
                $phasesGrades[$phaseId] = "-1"; // Phase Not selected
            }
        }

        $phases = array(
            'phasesNames' => $phasesNames,
            'phasesWeights' => $phasesWeights,
            'phasesGrades' => $phasesGrades
        );

        return $phases;
    }

    public function downloadNotice($selectiveProcessId, $courseId){

        $selectiveProcess = $this->process_model->getById($selectiveProcessId);
        $noticePath = $selectiveProcess->getNoticePath();
        $this->load->helper('download');
        if(file_exists($noticePath)){
            force_download($noticePath, NULL);
        }
        else{
            $status = "danger";
            $message = "Nenhum arquivo encontrado.";
            $this->session->set_flashdata($status, $message);
            $this->downloadNotice($selectiveProcessId, $courseId);
        }
    }

}
