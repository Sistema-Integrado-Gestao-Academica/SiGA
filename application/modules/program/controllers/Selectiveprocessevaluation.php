<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(MODULESPATH."/auth/constants/GroupConstants.php");
require_once(MODULESPATH."/program/constants/SelectionProcessConstants.php");
require_once(MODULESPATH."/program/exception/SelectionProcessException.php");

class SelectiveProcessEvaluation extends MX_Controller {


    public function __construct(){
        parent::__construct();

        $this->load->model("program/selectiveprocess_model", "process_model");
        $this->load->model("program/selectiveprocessevaluation_model", "process_evaluation_model");

        $this->load->helper("selectionprocess_helper");
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

        $phasesNames = $this->getPhasesNames($candidates);
        $candidates = $this->groupCandidates($candidates);

        $currentPhaseProcess = $this->process_evaluation_model->getPhaseProcessIdByPhaseId($processId, $phaseId);

        $data = array(
            'candidates' => $candidates,
            'phasesNames' => $phasesNames,
            'teacherId' => $teacherId,
            'currentPhaseProcessId' => $currentPhaseProcess->id,
            'docs' => FALSE
        );

       
        loadTemplateSafelyByGroup(GroupConstants::TEACHER_GROUP, "program/selection_process_evaluation/evaluate", $data);
    }

    private function getPhasesNames($candidates){
        $phasesNames = array();
        if($candidates){
            foreach ($candidates as $key => $candidate) {
                $processphaseId = $candidate['id_process_phase'];
                $phasesNames[$processphaseId] = $this->process_evaluation_model->getPhaseNameByPhaseProcessId($processphaseId);
            }
        }


        return $phasesNames;
    }

    private function groupCandidates($candidates){

        $candidatesEvaluations = array();
        if($candidates){
            foreach ($candidates as $candidate) {
                $candidateId = $candidate['candidate_id'];
                unset($candidate['candidate_id']);
                $candidatesEvaluations[$candidateId][] = $candidate;
            }
        }

        $candidatesEvaluations = $this->groupByProcessPhase($candidatesEvaluations);


        return $candidatesEvaluations;
    }

    private function groupByProcessPhase($candidatesEvaluations){

        $evaluations = array();
        if($candidatesEvaluations){
            foreach ($candidatesEvaluations as $key => $candidateEvaluation) {
                foreach ($candidateEvaluation as $evaluation) {
                    $idProcessPhase = $evaluation['id_process_phase'];
                    $evaluations[$key][$idProcessPhase][] = $evaluation;
                }
            }
        }

        return $evaluations;
    }

    public function saveCandidateGrade(){
        $self = $this;
        withPermissionAnd(PermissionConstants::SELECTION_PROCESS_EVALUATION,
            function() use($self){
                $self->checkIfIsLoggedTeacher();
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

            $saved = $this->process_evaluation_model->saveCandidateGrade($grade, $teacherId, $subscriptionId, $phaseprocessId, $approved);
            if($saved){
                $data = array('grade' => $grade, 'approved' => $approved);
                // $labelCandidate = $this->getCandidateFinalResult($);
                // $phase = $this->process_evaluation_model->getPassingScoreOfPhaseByProcessPhaseId($phaseprocessId);
                // $approved = $grade >= $phase->grade ? TRUE : FALSE;
                // $labelCandidate = $approved ? "<b class='text text-success'>Aprovado</b>" : "<b class='text text-warning'>Reprovado</b>"; 
                $response = array(
                    'type' => "success",
                    'message' => "Nota salva com sucesso"
                    // 'label' => $labelCandidate
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
}

        // $candidateApproved = ($candidateApproved && $evaluation['approved']) || FALSE;
        // $evaluatedForAll = ($evaluatedForAll && !is_null($evaluation['grade'])) || FALSE;
