
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


        $data = array(
            'course' => $course,
            'selectiveProcesses' => $selectiveProcesses
        );

        $data = $data + $configData;

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

    public function openSelectiveProcess($courseId){

        $this->load->model("program/course_model");
        $course = $this->course_model->getCourseById($courseId);

        $this->load->module("program/phase");
        $phases = $this->phase->getAllPhases();

        $data = array(
            'course' => $course,
            'phases' => $phases,
        );

        loadTemplateSafelyByPermission(PermissionConstants::SELECTION_PROCESS_PERMISSION, "program/selection_process/new", $data);
    }


    private function  getCourseSelectiveProcesses($courseId){

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
                            $process[SelectiveProcess_model::ID_ATTR],
                            $process['total_vacancies'],
                            $process['status']
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
                            $process[SelectiveProcess_model::ID_ATTR],
                            $process['total_vacancies'],
                            $process['status']
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
                    $statusByDate = getProcessStatusByDate($selectionProcess);
                    $selectionPtrocess = $statusByDate != $process['status']
                                        ? $this->changeProcessStatus($selectionProcess, $statusByDate)
                                        : $selectionProcess;
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

        // If process has a notice path it was already divulgated
        $noticePath = $selectiveProcess->getNoticePath();
        $canNotEdit = is_null($noticePath) ? FALSE : TRUE;

        $this->load->module("program/phase");
        $allPhases = $this->phase->getAllPhases();

        $phases = $this->getProcessPhasesToEdit($selectiveProcess, $allPhases);

        $editProcessData = array(
            'process' => $selectiveProcess,
            'processId' => $processId,
            'phases' => $phases,
            'allPhases' => $allPhases,
            'canNotEdit' => $canNotEdit
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

        $phases = array();
        $processPhases = $selectiveProcess->getSettings()->getPhases();

        foreach ($allPhases as $phase) {
            $hasThePhase = FALSE;
            $phaseId = $phase->getPhaseId();
            if(!empty($processPhases)){
                foreach ($processPhases as $processPhase) {
                    $processPhaseId = $processPhase->getPhaseId();
                    if($phaseId == $processPhaseId){
                        $name = $processPhase->getPhaseName();
                        if($phaseId != SelectionProcessConstants::HOMOLOGATION_PHASE_ID){
                            $weight = $processPhase->getWeight();
                            $grade = $processPhase->getGrade();
                            $knockoutPhase = $processPhase->isKnockoutPhase();
                        }
                        else{
                            $weight = 0;
                            $grade = 0;
                            $knockoutPhase = FALSE;
                        }
                        $phases[$phaseId] = array(
                            'name' => $name,
                            'weight' => $weight,
                            'grade' => $grade,
                            'knockoutPhase' => $knockoutPhase
                        ); 
                        $hasThePhase = TRUE;
                        break;
                    }
                }
            }
            if(!$hasThePhase){
                $phases[$phaseId] = array(
                    'name' => $phase->getPhaseName(),
                    'weight' => "-1", // Phase not selected
                    'grade' => "-1",// Phase not selected
                    'knockoutPhase' => TRUE
                ); 
            }
        }

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

    private function changeProcessStatus($selectionProcess, $statusByDate){

        // if($statusByDate == SelectionProcessConstants::OPEN_FOR_SUBSCRIPTIONS){
        //     $selectionProcess->setStatus($statusByDate);
        //     $this->process_model->changeProcessStatus($selectionProcess->getId(), $statusByDate);
        // }
        // else{
            $selectionProcess->setSuggestedPhase($statusByDate);
        // }

        return $selectionProcess;
    }

    public function goToNextPhase($processId, $courseId){
        $self = $this;
        withPermissionAnd(
            PermissionConstants::SELECTION_PROCESS_PERMISSION,
            function() use ($self, $courseId){
                return checkIfUserIsSecretary($courseId);
            },
            function() use ($self, $processId, $courseId){
                $statusByDate = $self->input->post("suggested_phase");

                $self->load->service(
                    'program/SelectiveProcessPhaseChange',
                    'phase_change_service'
                );

                try{
                    $changed = $self
                        ->phase_change_service
                        ->changeProcessPhase($processId, $statusByDate);

                    $type = $changed ? "success" : "danger";
                    $message = $changed
                        ? "O processo foi avançado com sucesso."
                        : "Não foi possível passar para a próxima fase. Tente novamente.";

                    getSession()->showFlashMessage($type, $message);
                    $self->courseSelectiveProcesses($courseId);
                } catch (SelectionProcessException $e) {

                }
            }
        );

    }

    public function showResults($processId){
        $selectiveProcess = $this->process_model->getById($processId);

        $this->load->model("program/selectiveprocessevaluation_model", "process_evaluation_model");
        $allProcessCandidates = $this->process_evaluation_model->getProcessCandidates($processId);

        $this->load->module("program/selectiveprocessevaluation");
        $allProcessCandidates = $this->selectiveprocessevaluation->getCandidates($allProcessCandidates);

        $candidatesResults = [];
        $resultCandidatesByPhase = [];
        $status =  $selectiveProcess->getStatus();
        $phasesResultPerCandidate = [];
        if($allProcessCandidates){

            foreach ($allProcessCandidates as $candidateId => $evaluations) {
                foreach ($evaluations as $phaseprocessId => $evaluation) {
                    $candidatesResults[$phaseprocessId][$candidateId] = $evaluation['phase_result'];
                }
            }

            if($candidatesResults){
                foreach ($candidatesResults as $key => $results) {
                    $hasResult = TRUE;
                    $phase = $this->process_evaluation_model->getPhaseNameByPhaseProcessId($key);
                    $resultOfCandidatesInPhase = array();
                    foreach ($results as $candidateId => $result) {
                        if($phase->phase_name == SelectionProcessConstants::HOMOLOGATION_PHASE){
                            $hasResult = TRUE;
                        }
                        else{
                            $hasResult = $result['hasResult'] && $hasResult;
                        }

                        if($hasResult){
                            $label = $this->selectiveprocessevaluation->getCandidatePhaseResultLabel($result);
                            $result['label'] = $label;
                            $resultOfCandidatesInPhase[$candidateId] = $result;

                            if($status === SelectionProcessConstants::FINISHED){
                                $phaseInfo =  array('phase_weight' => $phase->weight);
                                $phasesResultPerCandidate[$candidateId][$phase->phase_name] = $result + $phaseInfo;
                            }
                        }
                    }
                    if(!empty($resultOfCandidatesInPhase)){
                        $resultCandidatesByPhase[$phase->phase_name] = $resultOfCandidatesInPhase;
                    }
                }
            }
        }

        if($phasesResultPerCandidate){
            $quantityOfPhases = sizeof($resultCandidatesByPhase);
            $selectedCandidates = $this->getFinalResult($quantityOfPhases, $phasesResultPerCandidate);
            $resultCandidatesByPhase = $selectedCandidates + $resultCandidatesByPhase;
        }

        $data = array(
            'resultCandidatesByPhase' => $resultCandidatesByPhase,
            'process' => $selectiveProcess
        );

        loadTemplateSafelyByPermission(PermissionConstants::SELECTION_PROCESS_PERMISSION, "program/selection_process/results", $data);
    }

    private function getFinalResult($quantityOfPhases, $phasesResultPerCandidate){

        $selectedCandidates = array();
        $candidatesScore = array();
        $quantityOfPhases = $quantityOfPhases - 1;
        if($phasesResultPerCandidate){
            foreach ($phasesResultPerCandidate as $candidateId => $phasesResults) {
                unset($phasesResults[SelectionProcessConstants::HOMOLOGATION_PHASE]);
                $candidatePoints = 0;
                $totalWeight = 0;
                $approvedTimes = 0;
                foreach ($phasesResults as $phaseName => $phaseResult) {
                    $phaseWeight = $phaseResult['phase_weight'];
                    $candidatePoints += ($phaseWeight * $phaseResult['average']);
                    $totalWeight += $phaseWeight;                        
                    
                    $candidatesScore[$candidateId][] = array(
                        'phaseName' => $phaseName,
                        'average' => $phaseResult['average'],
                        'phaseWeight' => $phaseWeight,
                    );
                    $approvedTimes = $phaseResult['approved'] ? $approvedTimes + 1 : $approvedTimes;
                }

                $candidatePointsAverage = $candidatePoints/$totalWeight;
                $candidatesScore[$candidateId]['final_average'] = $candidatePointsAverage;
                $selected = $approvedTimes == $quantityOfPhases ? TRUE : FALSE; 
                $candidatesScore[$candidateId]['selected'] = $selected;
            }
        }

        arsort($candidatesScore);
        $selectedCandidates['Final'] = $candidatesScore;

        return $selectedCandidates;
    }

    public function generatePDF(){

        $candidates = $this->input->post('candidates');
        $processId = $this->input->post('processId');

        $process = $this->process_model->getById($processId);

        $data = array(
            'candidates' => json_decode($candidates),
            'phaseName' => $this->input->post('phaseName'),
            'processName' => $process->getName(),
            'processId' => $process->getId()
        );

        loadTemplateSafelyByPermission(PermissionConstants::SELECTION_PROCESS_PERMISSION, "program/selection_process/phase_result", $data);

    }
}
