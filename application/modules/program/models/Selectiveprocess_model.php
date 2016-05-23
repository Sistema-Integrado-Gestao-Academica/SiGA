
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH."/constants/SelectionProcessConstants.php");
require_once(APPPATH."/exception/SelectionProcessException.php");
require_once(APPPATH."/data_types/selection_process/SelectionProcess.php");
require_once(APPPATH."/data_types/selection_process/RegularStudentProcess.php");
require_once(APPPATH."/data_types/selection_process/SpecialStudentProcess.php");
require_once(APPPATH."/data_types/selection_process/ProcessSettings.php");

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
				$processId = $savedProcess[self::ID_ATTR];

				$this->saveProcessPhases($process, $processId);

				return $processId;
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

	public function updateNoticeFile($processId, $noticePath){

		$this->db->where(self::ID_ATTR, $processId);
		$updated = $this->db->update(self::SELECTION_PROCESS_TABLE, array(
			self::NOTICE_PATH_ATTR => $noticePath
		));

		return $updated;
	}

	public function getCourseSelectiveProcesses($courseId){

		$foundProcesses = $this->get(self::COURSE_ATTR, $courseId, FALSE);

		return $foundProcesses;
	}

	public function getById($processId){

		$foundProcess = $this->get(self::ID_ATTR, $processId);

		if($foundProcess !== FALSE){

			if($foundProcess[self::PROCESS_TYPE_ATTR] === SelectionProcessConstants::REGULAR_STUDENT){

				try{

					$selectiveProcess = new RegularStudentProcess(
						$foundProcess[self::COURSE_ATTR],
						$foundProcess[self::NOTICE_NAME_ATTR],
						$foundProcess[self::ID_ATTR]
					);

				}catch(SelectionProcessException $e){
					$selectiveProcess = FALSE;
				}
			}else{
				try{

					$selectiveProcess = new SpecialStudentProcess(
						$foundProcess[self::COURSE_ATTR],
						$foundProcess[self::NOTICE_NAME_ATTR],
						$foundProcess[self::ID_ATTR]
					);

				}catch(SelectionProcessException $e){
					$selectiveProcess = FALSE;
				}
			}

		}else{
			$selectiveProcess = FALSE;
		}

		return $selectiveProcess;
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