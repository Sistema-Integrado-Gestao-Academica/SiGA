<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(MODULESPATH."/auth/constants/PermissionConstants.php");
require_once(MODULESPATH."/auth/constants/GroupConstants.php");
require_once(MODULESPATH."/program/constants/SelectionProcessConstants.php");
require_once(MODULESPATH."/program/exception/SelectionProcessException.php");

class SelectiveProcessEvaluation extends MX_Controller {


    public function __construct(){
        parent::__construct();

        $this->load->model("program/selectiveprocess_model", "process_model");
        $this->load->model("program/selectiveprocessevaluation_model", "process_evaluation_model");

        $this->load->helper("selectionprocess");
    }

    public function index(){
        
        $openSelectiveProcesses = $this->process_evaluation_model->getProcessesForEvaluationByTeacher(getLoggedUserId());
        $processesPhase = $this->getProcessesPhases($openSelectiveProcesses);

        $data = array(
            'openSelectiveProcesses' => $openSelectiveProcesses,
            'processesPhase' => $processesPhase);
       
        loadTemplateSafelyByGroup(GroupConstants::TEACHER_GROUP, "program/selection_process_evaluation/index", $data);
    }

    private function getProcessesPhases($openSelectiveProcesses){

        $processesPhase = array();
        $phasesWithStatus = array(
            SelectionProcessConstants::IN_HOMOLOGATION_PHASE => SelectionProcessConstants::HOMOLOGATION_PHASE_ID, 
            SelectionProcessConstants::IN_PRE_PROJECT_PHASE => SelectionProcessConstants::PRE_PROJECT_EVALUATION_PHASE_ID, 
            SelectionProcessConstants::IN_WRITTEN_TEST_PHASE => SelectionProcessConstants::WRITTEN_TEST_PHASE_ID, 
            SelectionProcessConstants::IN_ORAL_TEST_PHASE => SelectionProcessConstants::ORAL_TEST_PHASE_ID
        );
        
        if($openSelectiveProcesses){
            foreach ($openSelectiveProcesses as $process) {
                $id = $process->getId();
                $status = $process->getStatus();
                $processesPhase[$id]['canEvaluate'] = 
                    $status == SelectionProcessConstants::IN_PRE_PROJECT_PHASE ||
                    $status == SelectionProcessConstants::IN_WRITTEN_TEST_PHASE ||
                    $status == SelectionProcessConstants::IN_ORAL_TEST_PHASE ? TRUE : FALSE;
                $processesPhase[$id]['phaseId'] = isset($phasesWithStatus[$status]) ? $phasesWithStatus[$status] : FALSE;
            }
        }

        return $processesPhase;
    }

    public function showTeacherCandidates($processId, $phaseId){

        $teacherId = getLoggedUserId();

        $phaseName = getPhaseName($phaseId);
        $candidates = $this->process_evaluation_model->getTeacherCandidates($teacherId, $processId);

        $candidates = $this->getCandidates($candidates, TRUE);
        $phasesNames = $this->getPhasesNames($candidates);
        $currentPhaseProcess = $this->process_evaluation_model->getPhaseProcessIdByPhaseId($processId, $phaseId);

        $docs = $this->getCandidatesDocs($candidates);
        
        $data = array(
            'candidates' => $candidates,
            'phasesNames' => $phasesNames,
            'teacherId' => $teacherId,
            'currentPhaseProcessId' => $currentPhaseProcess->id,
            'docs' => $docs
        );

       
        loadTemplateSafelyByPermission(
            PermissionConstants::SELECTION_PROCESS_EVALUATION, 
            "program/selection_process_evaluation/evaluate", 
            $data
        );
    }

    public function getCandidates($evaluations, $resultInLabelForm = FALSE){
        
        $candidatesEvaluations = array();
        if($evaluations){
            $this->load->model("program/selectiveProcessSubscription_model", "subscription_model");
            foreach ($evaluations as $key => $evaluation) {
                $idSubscription = $evaluation['id_subscription'];
                $subscription = $this->subscription_model->getBySubscriptionId($idSubscription);
                $candidateId = $subscription['candidate_id'];
                $idProcessPhase = $evaluation['id_process_phase'];
                $candidatesEvaluations[$candidateId][$idProcessPhase][$idSubscription][] = $evaluation;
                if($resultInLabelForm){
                    $phaseResult = $this->getCandidatePhaseResultLabel($evaluation['id_subscription'], $idProcessPhase);
                }
                else{
                    $phaseResult = $this->getCandidatePhaseResult($evaluation['id_subscription'], $idProcessPhase);
                }
                $candidatesEvaluations[$candidateId][$idProcessPhase]['phase_result'] = $phaseResult;
            }
        }
        

        return $candidatesEvaluations;
    }

    private function getPhasesNames($candidates){
        $phasesNames = array();
        if($candidates){
            $candidate = reset($candidates);
            foreach ($candidate as $processphaseId => $candidate) {
                $phasesNames[$processphaseId] = $this->process_evaluation_model->getPhaseNameByPhaseProcessId($processphaseId);
            }
        }

        return $phasesNames;
    }


    public function saveCandidateGrade(){
        $self = $this;
        withPermissionAnd(PermissionConstants::SELECTION_PROCESS_EVALUATION,
            function() use($self){
                return $self->checkIfIsLoggedTeacher();
            },
            function() use($self){
                $self->saveGrade();
            }
        );
    }

    private function checkIfIsLoggedTeacher(){
        $teacherId = $this->input->post("teacherId");
        return $teacherId == getLoggedUserId();
    }

    private function saveGrade(){
        define('GRADE_REQUIRED', "A nota é obrigatória.");
        define('INVALID_GRADE', "Nota inválida. A nota deve ser de 0 a 100");

        $teacherId = $this->input->post("teacherId");
        $grade = $this->input->post("grade");
        
        $validGrade = !empty($grade) && $grade >= 0 && $grade <=100;
        if($validGrade){
            $subscriptionId = $this->input->post("subscriptionId");
            $phaseprocessId = $this->input->post("phaseprocessId");

            $this->load->service(
                "program/SelectionProcessEvaluation",
                "evaluation_service"
            );

            $data = ['grade' => $grade];
            $saved = $this->process_evaluation_model->saveOrUpdate($subscriptionId, $teacherId, $phaseprocessId, $data);
            if($saved){
                $labelCandidate = $this->getCandidatePhaseResultLabel($subscriptionId, $phaseprocessId);

                $response = array(
                    'type' => "success",
                    'message' => "Nota salva com sucesso",
                    'label' => $labelCandidate
                );
            }
            else{
                $response = array(
                    'type' => "danger",
                    'message' => "Não foi possível salvar a nota do candidato. Tente novamente."
                );
            }
        }
        else{
            $message = empty($grade) ? GRADE_REQUIRED : INVALID_GRADE;
            $response = array(
                'type' => "danger",
                'message' => $message
            );
        }

        echo json_encode($response);
    }

    public function getCandidatePhaseResult($subscriptionId, $phaseprocessId){

        $hasResult = FALSE;
        $candidateGradesOnPhase = $this->process_evaluation_model->getCandidatePhaseEvaluations($subscriptionId, $phaseprocessId); 
        $phaseEvaluationElements = $this->process_evaluation_model->getPhaseEvaluationElementsOfPhaseByProcessPhaseId($phaseprocessId);

        $totalGrade = 0;
        foreach ($candidateGradesOnPhase as $result) {
            
            if(is_null($result['grade'])){
                $hasResult = FALSE;
                break;
            }
            else{
                $hasResult = TRUE;
                $totalGrade += $result['grade'];
            }

        }

        $approvedOnPhase = FALSE;
        $knockoutPhase = $phaseEvaluationElements->knockout_phase;
        $passingScore = $phaseEvaluationElements->grade;
        if($hasResult && $knockoutPhase){
            if(($totalGrade/2) >= $passingScore){
                $approvedOnPhase = TRUE;
            }
        }
        elseif (!$knockoutPhase) {
            $approvedOnPhase = TRUE;
        }

        $phaseResult = ['hasResult' => $hasResult, 'approved' => $approvedOnPhase, 'knockoutPhase' => $knockoutPhase];

        return $phaseResult;
    }

    public function getCandidatePhaseResultLabel($subscriptionId, $phaseprocessId){

        $phaseResult = $this->getCandidatePhaseResult($subscriptionId, $phaseprocessId);

        if($phaseResult['hasResult']){
            if($phaseResult['approved'] && $phaseResult['knockoutPhase']){
                $labelCandidate =  "<b class='text text-success'>Aprovado</b>";    
            }
            elseif ($phaseResult['approved'] && !$phaseResult['knockoutPhase']) {
                $labelCandidate =  "<b class='text text-success'>Classificado</b>";    
            }
            else{

                $labelCandidate =  "<b class='text text-danger'>Reprovado</b>"; 
            }
        }
        else{

            $labelCandidate = "<b class='text text-warning'>-</b>";
        }

        return $labelCandidate;

    }


    private function getCandidatesDocs($candidates){
        
        $this->load->model(
            'program/selectiveProcessSubscription_model',
            'process_subscription_model'
        );

        $docs = [];
        foreach ($candidates as $candidate) {
            $phaseId = reset($candidate);
            $subscriptionId = key($phaseId);             
            $docs[$subscriptionId] = $this->process_subscription_model->getSubscriptionDocs($subscriptionId, TRUE);
        }
        
        return $docs;
    }

}

