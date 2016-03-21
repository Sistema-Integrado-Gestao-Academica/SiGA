
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SelectiveProcess_model extends CI_Model {

	const SELECTION_PROCESS_TABLE = "selection_process";

	const ID_ATTR = "id_process";
	const COURSE_ATTR = "id_course";
	const PROCESS_TYPE_ATTR = "process_type";
	const NOTICE_NAME_ATTR = "notice_name";
	const NOTICE_PATH_ATTR = "notice_path";
	const START_DATE_ATTR = "start_date";
	const END_DATE_ATTR = "end_date";
	const PHASE_ORDER_ATTR = "phase_order";

	// Association table data
	const PROCESS_PHASE_TABLE = "process_phase";
	const ID_PHASE_ATTR = "id_phase";
	const PROCESS_PHASE_WEIGHT_ATTR = "weight";

	// Errors constants
	const COULDNT_SAVE_SELECTION_PROCESS = "Não foi possível salvar o processo seletivo. Cheque os dados informados.";
	const REPEATED_NOTICE_NAME = "O nome do edital informado já existe. Nome informado: ";

	public function save($process){

		
		$courseId = $process->getCourse();
		$processType = $process->getType();
		$noticeName = $process->getName();
		$startDate = $process->getSettings()->getYMDStartDate();
		$endDate = $process->getSettings()->getYMDEndDate();
		$phasesOrder = serialize($process->getSettings()->getPhasesOrder());

		
		$processToSave = array(
			self::COURSE_ATTR => $courseId,
			self::PROCESS_TYPE_ATTR => $processType,
			self::NOTICE_NAME_ATTR => $noticeName,
			self::START_DATE_ATTR => $startDate,
			self::END_DATE_ATTR => $endDate,
			self::PHASE_ORDER_ATTR => $phasesOrder
		);
		
		$previousProcess = $this->getByName($noticeName);

		
		// Does not exists this selection process yet
		if($previousProcess === FALSE){

			// Saves the selection process basic data
			$this->db->insert(self::SELECTION_PROCESS_TABLE, $processToSave);

			$savedProcess = $this->getByName($noticeName);

			if($savedProcess !== FALSE){
				$id = $savedProcess[self::ID_ATTR];

				$this->saveProcessPhases($process, $id);
			}else{
				// For some reason did not saved the selection process
				throw new SelectionProcessException(self::COULDNT_SAVE_SELECTION_PROCESS);
			}
		}else{
			throw new SelectionProcessException(self::REPEATED_NOTICE_NAME.$noticeName);
		}
	}

	private function saveProcessPhases($process, $processId){

		$phases = $process->getSettings()->getPhases();

		foreach($phases as $phase){
			$phaseId = $phase->getPhaseId();

			if($phase->getPhaseName() === SelectionProcessConstants::HOMOLOGATION_PHASE){

				$phaseWeight = SelectionProcessConstants::HOMOLOGATION_PHASE_WEIGHT;
			}else{
				$phaseWeight = $phase->getWeight();
			}

			$this->db->insert(self::PROCESS_PHASE_TABLE, array(
				self::ID_ATTR => $processId,
				self::ID_PHASE_ATTR => $phaseId,
				self::PROCESS_PHASE_WEIGHT_ATTR => $phaseWeight
			));

		}
	}

	private function getByName($name){

		$process = $this->get(self::NOTICE_NAME_ATTR, $name);

		return $process;
	}

	private function get($attr, $value = FALSE, $unique = TRUE){

		if(is_array($attr)){
			$foundProcess = $this->db->get_where(self::SELECTION_PROCESS_TABLE, $attr);
		}else{
			
			$foundProcess = $this->db->get_where(self::SELECTION_PROCESS_TABLE, array($attr => $value));
		}

		if($unique){
			$foundProcess = $foundProcess->row_array();
		}else{
			$foundProcess = $foundProcess->result_array();
		}

		$foundProcess = checkArray($foundProcess);

		return $foundProcess;
	}
}