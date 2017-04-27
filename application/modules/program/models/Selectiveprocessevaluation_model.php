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
        	$this->db->update($this->TABLE, $data);
        }else{
        	$evaluation = array_merge($evaluationIdentifiers, $data);
        	$this->persist($evaluation);
        }
	}

	public function getProcessesForEvaluationByTeacher($teacherId){

		// Trocar process_type para status
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

    public function getTeacherCandidates($teacherId, $idProcess){

    	$query = "SELECT subs.candidate_id, eval.* ";
		$query .= "FROM `selection_process_user_subscription` subs ";
		$query .= "JOIN `selection_process_evaluation`eval ON `id_process_phase` IN (SELECT `id` FROM `process_phase` WHERE `id_process` = {$idProcess}) ";
		$query .= "WHERE `id_subscription` IN (SELECT `id_subscription` FROM `selection_process_evaluation` WHERE `id_teacher` = {$teacherId}) ";

		$result = $this->db->query($query);

		return $result->result_array();
	}

	public function getEvaluationTeachers($subscriptionId){
		$this->db->distinct();
		$this->db->select('u.name, u.email, spe.*');
		$this->db->from("users u");
		$this->db->join("selection_process_evaluation spe ", "spe.id_teacher=u.id");
		$this->db->where("spe.id_subscription", $subscriptionId);
		$teachers = checkArray($this->db->get()->result_array());

		return $teachers;
	}
}