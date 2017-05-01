<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(MODULESPATH."program/constants/SelectionProcessConstants.php");
require_once(MODULESPATH."program/exception/SelectionProcessException.php");

class SelectiveProcessEvaluation_model extends CI_Model {

	public $TABLE = "selection_process_evaluation";
	public $TEACHER_PROCESS_TABLE = "teacher_selection_process";
	public $PROCESS_TABLE = "selection_process";

	public function saveOrUpdate(
		$subscriptionId, $teacherId, $processPhaseId, $data=[]){

		$evaluationIdentifiers = [
            'id_subscription' => $subscriptionId,
            'id_teacher' => $teacherId,
            'id_process_phase' => $processPhaseId
        ];

        $foundEvaluation = $this->get($evaluationIdentifiers);

        if($foundEvaluation !== FALSE){
        	$this->db->where($evaluationIdentifiers);
        	$saved = $this->db->update($this->TABLE, $data);
        }else{
        	$evaluation = array_merge($evaluationIdentifiers, $data);
        	$saved = $this->persist($evaluation);
        }

        return $saved;
	}

	public function getProcessesForEvaluationByTeacher($teacherId){

		$this->db->select("process.*");
		$this->db->join("selection_process process", "process.id_process = teacher_process.id_process");
		$this->db->from("teacher_selection_process teacher_process");
		$this->db->where("teacher_process.id_teacher", $teacherId);

		$processes = $this->db->get()->result_array();

		$processes = $this->getProcessesOnObject(checkArray($processes));

		return $processes;
	}

	public function getProcessesOnObject($processes){

		$this->load->model("program/selectionprocess_model", "process_model");
		$selectionProcesses = array();
		if($processes){
			foreach ($processes as $process) {
				$selectionProcesses[] = $this->process_model->convertArrayToObject($process);
			}
		}

		return $selectionProcesses;
	}

	public function getPhaseNameByPhaseProcessId($processphaseId){

		$this->db->select("phase.phase_name");
		$this->db->join("phase", "phase.id_phase = pp.id_phase");
		$this->db->where("id", $processphaseId);

		$phaseName = $this->db->get("process_phase pp")->row();

		return $phaseName;
	}

	public function getPhaseProcessIdByPhaseId($processId, $phaseId){

		$this->db->select("id");
		$this->db->where("id_phase", $phaseId);
		$this->db->where("id_process", $processId);

		$idPhaseProcess = $this->db->get("process_phase")->row();

		return $idPhaseProcess;
	}

    public function getPassingScoreOfPhaseByProcessPhaseId($idPhaseProcess){

		$this->db->select("grade");
		$this->db->where("id", $idPhaseProcess);
		$phase = $this->db->get("process_phase")->row();

		return $phase->grade;
	}

    public function getTeacherCandidates($teacherId, $idProcess){

    	$query = "SELECT * FROM `selection_process_evaluation`";
		$query .= "WHERE `id_process_phase` IN (SELECT `id` FROM `process_phase` WHERE `id_process` = {$idProcess} AND `id_phase` != 1) ";
		$query .= "AND `id_subscription` IN (SELECT `id_subscription` FROM `selection_process_evaluation` WHERE `id_teacher` = {$teacherId}) ";
		$query .= "ORDER BY `id_teacher`, `id_process_phase`";

		$evaluations = $this->db->query($query);

		return $evaluations->result_array();
	}


	public function getEvaluationTeachers($subscriptionId){
		$this->db->distinct();
		$this->db->select('u.id, u.name, u.email');
		$this->db->from("users u");
		$this->db->join("selection_process_evaluation spe ", "spe.id_teacher=u.id");
		$this->db->where("spe.id_subscription", $subscriptionId);
		$teachers = checkArray($this->db->get()->result_array());

		return $teachers;
	}

	public function deleteSubscriptionEvaluations($subscriptionId){
		$this->db->where('id_subscription', $subscriptionId);
		$this->db->delete('selection_process_evaluation');
	}

	public function getCandidatePhaseEvaluations($idSubscription, $idPhaseProcess){

		$this->db->select("grade");
		$this->db->from($this->TABLE);
		$this->db->where("id_subscription", $idSubscription);
		$this->db->where("id_process_phase", $idPhaseProcess);

		return $this->db->get()->result_array();
	}

	public function getProcessCandidates($processId){

		$query = "SELECT * FROM `selection_process_evaluation`";
		$query .= "WHERE `id_process_phase` IN (SELECT `id` FROM `process_phase` WHERE `id_process` = {$processId}) ";
		$query .= "ORDER BY `id_subscription`";


		$evaluations = $this->db->query($query);

		return $evaluations->result_array();
	}
}