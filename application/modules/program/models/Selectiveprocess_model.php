
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(MODULESPATH."program/constants/SelectionProcessConstants.php");
require_once(MODULESPATH."program/exception/SelectionProcessException.php");
require_once(MODULESPATH."program/domain/selection_process/SelectionProcess.php");
require_once(MODULESPATH."program/domain/selection_process/RegularStudentProcess.php");
require_once(MODULESPATH."program/domain/selection_process/SpecialStudentProcess.php");
require_once(MODULESPATH."program/domain/selection_process/ProcessSettings.php");

class SelectiveProcess_model extends CI_Model {

	public $TABLE = "selection_process";

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

	// Methods 
	const INSERT_ON_DB = 1;
	const UPDATE_ON_DB = 2;

	// Errors constants
	const COULDNT_SAVE_SELECTION_PROCESS = "Não foi possível salvar o processo seletivo. Cheque os dados informados.";
	const REPEATED_NOTICE_NAME = "O nome do edital informado já existe. Nome informado: ";

	public function save($process){
		$processToSave = $this->getArrayToSave($process);
		$noticeName = $process->getName();
		$previousProcess = $this->getByName($noticeName);


		// Does not exists this selection process yet
		if($previousProcess === FALSE){

			// Saves the selection process basic data
			$this->db->insert($this->TABLE, $processToSave);

			$savedProcess = $this->getByName($noticeName);

			if($savedProcess !== FALSE){
				$processId = $savedProcess[self::ID_ATTR];

				$this->saveProcessPhases($process, $processId, self::INSERT_ON_DB);

				return $processId;
			}else{
				// For some reason did not saved the selection process
				throw new SelectionProcessException(self::COULDNT_SAVE_SELECTION_PROCESS);
			}
		}else{
			throw new SelectionProcessException(self::REPEATED_NOTICE_NAME.$noticeName);
		}
	}

	public function update($process){
		$processToSave = $this->getArrayToSave($process);
		$processId = $process->getId();
		$this->db->where('id_process', $processId);
		$updated = $this->db->update($this->TABLE, $processToSave);

		if($updated){

			$this->saveProcessPhases($process, $processId, self::UPDATE_ON_DB);

			return $processId;
		}
		else{
			// For some reason did not saved the selection process
			throw new SelectionProcessException(self::COULDNT_SAVE_SELECTION_PROCESS);
		}
	}

	private function getArrayToSave($process){
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

		return $processToSave;

	}

	private function saveProcessPhases($process, $processId, $method){

		$phases = $process->getSettings()->getPhases();

		foreach($phases as $phase){
			$phaseId = $phase->getPhaseId();

			if($phase->getPhaseName() === SelectionProcessConstants::HOMOLOGATION_PHASE){

				$phaseWeight = SelectionProcessConstants::HOMOLOGATION_PHASE_WEIGHT;
			}else{
				$phaseWeight = $phase->getWeight();
			}

			$arrayToSave = array(
				self::ID_ATTR => $processId,
				self::ID_PHASE_ATTR => $phaseId,
				self::PROCESS_PHASE_WEIGHT_ATTR => $phaseWeight
			);	

			if($method == self::INSERT_ON_DB){
				$this->db->insert(self::PROCESS_PHASE_TABLE, $arrayToSave);
				
			}
			else{
				$this->db->where('id_process', $processId);
				$this->db->update(self::PROCESS_PHASE_TABLE, $arrayToSave);
			}

		}

	}

	public function updateNoticeFile($processId, $noticePath){

		$this->db->where(self::ID_ATTR, $processId);
		$updated = $this->db->update($this->TABLE, array(
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
			
			$phasesOrder = unserialize($foundProcess[SelectiveProcess_model::PHASE_ORDER_ATTR]);
	        $startDate = convertDateTimeToDateBR($foundProcess[SelectiveProcess_model::START_DATE_ATTR]);
	        $endDate = convertDateTimeToDateBR($foundProcess[SelectiveProcess_model::END_DATE_ATTR]);
	        $phases = $this->getPhases($foundProcess['id_process']);
	        try{
		        	$settings = new ProcessSettings(
		            $startDate,
		            $endDate,
		            $phases,
		            $phasesOrder
	        	);
	        }
	        catch(SelectionProcessException $e){
				$selectiveProcess = FALSE;
				throw new SelectionProcessException($e);
			}
			if($foundProcess[self::PROCESS_TYPE_ATTR] === SelectionProcessConstants::REGULAR_STUDENT){

				try{

					$selectiveProcess = new RegularStudentProcess(
						$foundProcess[self::COURSE_ATTR],
						$foundProcess[self::NOTICE_NAME_ATTR],
						$foundProcess[self::ID_ATTR]
					);
					$selectiveProcess->addSettings($settings);

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
					$selectiveProcess->addSettings($settings);

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

	private function getProcessPhases($processId){
		$this->db->select(self::ID_PHASE_ATTR.",".self::PROCESS_PHASE_WEIGHT_ATTR);
		$this->db->from(self::PROCESS_PHASE_TABLE);
		$this->db->where(self::ID_ATTR, $processId);
		$processPhases = $this->db->get()->result_array();

        $processPhases = checkArray($processPhases);

        return $processPhases;
	}

    public function getPhases($processId){

        $processPhases = $this->getProcessPhases($processId);
        $phases = array();
        $validProcessPhases = !empty($processPhases) && !is_null($processPhases);
        if($validProcessPhases){
	        foreach ($processPhases as $processPhase) {
	            $processPhaseId = $processPhase['id_phase'];
	            $weight = $processPhase['weight'];
	            switch ($processPhaseId) {
	                case SelectionProcessConstants::HOMOLOGATION_PHASE_ID:
	                    $phase = new Homologation($processPhaseId);
	                    break;
	                case SelectionProcessConstants::PRE_PROJECT_EVALUATION_PHASE_ID:
	                    $phase = new PreProjectEvaluation($weight, FALSE, $processPhaseId);
	                    break;
	                case SelectionProcessConstants::WRITTEN_TEST_PHASE_ID:
	                    $phase = new WrittenTest($weight, FALSE, $processPhaseId);
	                    break;
	                case SelectionProcessConstants::ORAL_TEST_PHASE_ID:
	                    $phase = new OralTest($weight, FALSE, $processPhaseId);
	                    break;
	                default:
	                    $phase = NULL;
	                    break;
	            }
	            $phases[] = $phase;
	        }
        }

        return $phases;
    }

}