
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

    // Exceptions messages
    const NOTICE_FILE_ERROR_ON_UPLOAD = "Tente novamente.";
    const NOTICE_FILE_ERROR_ON_UPDATE = "Não foi possível salvar o arquivo do Edital. Tente novamente.";
    const NOTICE_FILE_SUCCESS = 'Processo Seletivo e edital salvo com sucesso!';

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

        $status = $this->getProcessStatus($selectiveProcesses);
        $data = array(
            'course' => $course,
            'selectiveProcesses' => $selectiveProcesses,
            'status' => $status
        );

        loadTemplateSafelyByPermission(PermissionConstants::SELECTION_PROCESS_PERMISSION, "program/selection_process/course_process", $data);
    }

    private function getProcessStatus($selectiveProcesses){

        $status = array();
        if($selectiveProcesses !== FALSE){
            foreach ($selectiveProcesses as $selectiveProcess) {
                $selectiveProcessId = $selectiveProcess->getId();
                $divulgation = $this->process_model->getProcessDivulgations($selectiveProcessId, TRUE);
                if(!is_null($divulgation)){
                    $divulgationDate = $divulgation['date'];
                    $divulgationDate = new Datetime($divulgationDate);
                    $today = new Datetime();
                    if($divulgationDate <= $today){
                        $status[$selectiveProcessId] = "<span class='label label-success'>".SelectionProcessConstants::DISCLOSED."</span>";
                    }
                    else{
                        $status[$selectiveProcessId] = "<span class='label label-warning'>".SelectionProcessConstants::NOT_DISCLOSED."</span>";
                    }
                }
                else{
                    $status[$selectiveProcessId] = "<span class='label label-warning'>".SelectionProcessConstants::NOT_DISCLOSED."</span>";
                }

            }
        }

        return $status;
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

        $courseId = $this->input->post("course");
        $processId = base64_decode($this->input->post("selection_process_id"));

        $message = $this->uploadNoticeFile($courseId, $processId);
        switch ($message) {
            case self::NOTICE_FILE_SUCCESS:
                $status = "success";
                $pathToRedirect = "program/selectiveprocess/courseSelectiveProcesses/{$courseId}";
                break;

            case self::NOTICE_FILE_ERROR_ON_UPDATE:
                $status = "danger";
                $pathToRedirect = "program/selectiveprocess/tryUploadNoticeFile/{$processId}";
                break;

            default:
                $status = "danger";
                $pathToRedirect = "program/selectiveprocess/tryUploadNoticeFile/{$processId}";
                break;
        }

        $this->session->set_flashdata($status, $message);
        redirect($pathToRedirect);
    }

    public function uploadNoticeFile($courseId, $processId){

        $this->load->library('upload');
        $process = $this->process_model->getById($processId);

        $this->load->model("program/course_model");
        $course = $this->course_model->getCourseById($courseId);

        $config = $this->setUploadOptions($process->getName(), $course["id_program"], $course["id_course"], $processId);

        $this->upload->initialize($config);
        $status = "";
        if($this->upload->do_upload("notice_file")){

            $noticeFile = $this->upload->data();
            $noticePath = $noticeFile['full_path'];

            $wasUpdated = $this->updateNoticeFile($processId, $noticePath);

            if($wasUpdated){
                $status = self::NOTICE_FILE_SUCCESS;
            }
            else{
                $status = self::NOTICE_FILE_ERROR_ON_UPDATE;
            }
        }
        else{
            // Errors on file upload
            $errors = $this->upload->display_errors();
            $status = $errors."<br>".self::NOTICE_FILE_ERROR_ON_UPLOAD.".";
        }

        return $status;
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

                $phasesOrder = unserialize($process[SelectiveProcess_model::PHASE_ORDER_ATTR]);
                $startDate = convertDateTimeToDateBR($process[SelectiveProcess_model::START_DATE_ATTR]);
                $endDate = convertDateTimeToDateBR($process[SelectiveProcess_model::END_DATE_ATTR]);
                $phases = $this->process_model->getPhases($process['id_process']);
                $phases = $this->process_model->sortPhasesBasedInOrder($phases, $phasesOrder);
                $settings = new ProcessSettings(
                    $startDate,
                    $endDate,
                    $phases,
                    $phasesOrder
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

    public function addTeacherToProcess(){
        $processId = $this->input->post('processId');
        $teacherId = $this->input->post('teacherId');
        $programId = $this->input->post('programId');
        $self = $this;
        withPermission(PermissionConstants::SELECTION_PROCESS_PERMISSION,
            function() use ($self, $processId, $teacherId, $programId){
                $self->process_model->addTeacherToProcess($processId, $teacherId);
                $self->updateDefineTeacherTables($processId, $programId);
            }
        );
    }

    public function removeTeacherFromProcess(){
        $processId = $this->input->post('processId');
        $teacherId = $this->input->post('teacherId');
        $programId = $this->input->post('programId');
        $self = $this;
        withPermission(PermissionConstants::SELECTION_PROCESS_PERMISSION,
            function() use ($self, $processId, $teacherId, $programId){
                $self->process_model->removeTeacherFromProcess($processId, $teacherId);
                $self->updateDefineTeacherTables($processId, $programId);
            }
        );
    }

    private function updateDefineTeacherTables($processId, $programId){
        $data = $this->getDefineTeachersViewData($processId, $programId);
        $teachers = $data['teachers'];
        $processTeachers = $data['processTeachers'];
        include(MODULESPATH.'program/views/selection_process/define_teachers_tables.php');
    }

    private function getDefineTeachersViewData($processId, $programId){

        $session = getSession();
        $user = $session->getUserData();
        $secretaryId = $user->getId();

        $this->load->model('program/program_model');
        $programsTeachers = $this->program_model->getProgramTeachers($programId);

        $processTeachers = $this->process_model->getProcessTeachers($processId);

        $data = array(
            'teachers' => $programsTeachers,
            'processTeachers' => $processTeachers
        );

        return $data;
    }

    private function getEditProcessViewData($processId, $courseId){
        $selectiveProcess = $this->process_model->getById($processId);
        $this->load->module("program/phase");
        $allPhases = $this->phase->getAllPhases();

        $phases = $this->getProcessPhasesToEdit($selectiveProcess, $allPhases);

        $noticePath = $selectiveProcess->getNoticePath();
        $names = explode("/", $noticePath);
        $noticeFileName = array_pop($names);

        $divulgation = $this->process_model->getProcessDivulgations($processId, TRUE);

        $editProcessData = array(
            'selectiveprocess' => $selectiveProcess,
            'processId' => $processId,
            'courseId' => $courseId,
            'phasesNames' => $phases['phasesNames'],
            'phasesWeights' => $phases['phasesWeights'],
            'noticeFileName' => $noticeFileName,
            'divulgation' => $divulgation
        );

        return $editProcessData;
    }

    public function edit($processId, $courseId){

        $editProcessData = $this->getEditProcessViewData($processId, $courseId);

        $this->load->model("program/course_model");
        $course = $this->course_model->getCourseById($courseId);
        $defineTeacherData = $this->getDefineTeachersViewData($processId, $course['id_program']);

        $data = $editProcessData + $defineTeacherData;

        $data['programId'] = $course['id_program'];

        loadTemplateSafelyByPermission(PermissionConstants::SELECTION_PROCESS_PERMISSION, "program/selection_process/edit", $data);
    }

    private function getProcessPhasesToEdit($selectiveProcess, $allPhases){

        $phasesNames = array();
        $phasesWeights = array();
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
                        }
                        else{
                            $phasesWeights[$phaseId] = "0";
                        }
                        $hasThePhase = TRUE;
                        break;
                    }
                }
            }
            if(!$hasThePhase){
                $phasesNames[$phaseId] = $phase->getPhaseName();
                $phasesWeights[$phaseId] = "-1"; // Phase Not selected
            }
        }

        $phases = array(
            'phasesNames' => $phasesNames,
            'phasesWeights' => $phasesWeights
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
            redirect("edit_selection_process/{$selectiveProcessId}/{$courseId}");
        }
    }

    public function loadDefineDatesPage($selectiveProcessId, $courseId){

        $selectiveProcess = $this->process_model->getById($selectiveProcessId);
        $processDivulgation = $this->process_model->getProcessDivulgations($selectiveProcessId, TRUE);

        $data = array(
            'selectiveprocess' => $selectiveProcess,
            'courseId' => $courseId,
            'processDivulgation' => $processDivulgation
        );

        $this->load->helper('selectionprocess');
        loadTemplateSafelyByPermission(PermissionConstants::SELECTION_PROCESS_PERMISSION, "program/selection_process/define_dates", $data);
    }

    public function divulgations($selectiveProcessId){
        
        $selectiveProcess = $this->process_model->getById($selectiveProcessId);
        $processDivulgations = $this->process_model->getProcessDivulgations($selectiveProcessId);

        $data = array(
            'selectiveprocess' => $selectiveProcess,
            'processDivulgations' => $processDivulgations
        );

        $this->load->helper('selectionprocess');


        loadTemplateSafelyByPermission(PermissionConstants::SELECTION_PROCESS_PERMISSION, "program/selection_process/divulgations", $data);
    }


    public function addDivulgation(){
        $processId = $this->input->post("process_id");
        $description = $this->input->post("description");
        $message = $this->input->post("message");
        $related_phase = $this->input->post("phase");
        $initial_divulgation = $this->input->post("initial_divulgation");

        $ids = array(
            "p" => $processId
        );

        $fieldId = "divulgation_file";
        $folderName = "divulgations";
        $allowedTypes = "jpg|png|pdf|jpeg";

        if (isset($_FILES['divulgation_file']) && is_uploaded_file($_FILES['divulgation_file']['tmp_name'])) {
           $filePath = uploadFile(FALSE, $ids, $fieldId, $folderName, $allowedTypes);
        }
        else{
           $filePath = NULL;
        }
        
        $today = new Datetime();
        $today = $today->format("Y/m/d");
        if($filePath || is_null($filePath)){
            $data = array(
                'id_process' => $processId, 
                'description' => $description,
                'message' => $message,
                'initial_divulgation' => $initial_divulgation,
                'date' => $today,
                'file_path' => $filePath 
            );
            if($related_phase !== "0"){
                $data['related_id_phase'] = $related_phase;
            }
            $saved = $this->process_model->saveProcessDivulgation($data);

            if($saved){
                $status = "success";
                $message = "Nova divulgação realizada com sucesso.";
            }
            else{
                $status = "danger";
                $message = "Não foi possível fazer a nova divulgação. Tente novamente.";
            }
        }
        else{
            $status = "danger";
            $errors = $this->upload->display_errors();
            $message = $errors."<br>".self::NOTICE_FILE_ERROR_ON_UPLOAD.".";
        }
        
        $this->session->set_flashdata($status, $message);
        redirect("selection_process/divulgations/{$processId}");             
    }

    public function downloadDivulgationFile($divulgationId){

        $divulgation = $this->process_model->getProcessDivulgationById($divulgationId);
        $filePath = $divulgation['file_path'];
        
        $this->load->helper('download');
        if(file_exists($filePath)){
            force_download($filePath, NULL);
        }
        else{
            $status = "danger";
            $message = "Nenhum arquivo encontrado.";
            $this->session->set_flashdata($status, $message);
            $processId = $divulgation['id_process'];
            redirect("selection_process/divulgations/{$processId}");             
        }
    }

    public function showTimeline($processId){
        $selectiveProcess = $this->process_model->getById($processId);
        $processDivulgations = $this->process_model->getProcessDivulgations($processId);

        $data = array(
            'selectiveprocess' => $selectiveProcess,
            'processDivulgations' => $processDivulgations
        );

        $this->load->helper('selectionprocess');

        loadTemplateSafelyByGroup(GroupConstants::GUEST_GROUP, "/home/divulgations", $data);
    }
    
    private function getCoursesName($selectiveProcess){
        
    }

}
