<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Program_Evaluation_model extends CI_Model {

	public function saveProgramEvaluation($evaluationData){

		$this->db->insert('program_evaluation', $evaluationData);

		$foundEvaluation = $this->getProgramEvaluation($evaluationData);

		if($foundEvaluation !== FALSE){
			$evaluationId = $foundEvaluation['id_program_evaluation'];
		}else{
			$evaluationId = FALSE;
		}

		return $evaluationId;
	}

	public function newDimensionType($dimensionName, $dimensionWeight){

		$alreadyExists = $this->checkIfDimensionNameAlreadyExists($dimensionName);

		if(!$alreadyExists){

			$this->db->insert('dimension_type', array(
				'dimension_type_name' => $dimensionName,
				'default_weight' => $dimensionWeight
			));

			$foundDimensionType = $this->getDimensionType(array('dimension_type_name' => $dimensionName));

			if($foundDimensionType !== FALSE){
				$wasSaved = TRUE;
			}else{
				$wasSaved = FALSE;
			}
		}else{
			$wasSaved = FALSE;
		}

		return $wasSaved;
	}

	private function checkIfDimensionNameAlreadyExists($dimensionName){

		$dimensionType = $this->getDimensionType(array('dimension_type_name' => $dimensionName));

		if($dimensionType === FALSE){
			$alreadyExists = FALSE;
		}else{
			$alreadyExists = TRUE;
		}

		return $alreadyExists;
	}

	private function getDimensionType($dimensionData){

		$dimensionType = $this->db->get_where('dimension_type', $dimensionData)->row_array();

		$dimensionType = checkArray($dimensionType);

		return $dimensionType;
	}

	public function addDimensionTypeToEvaluation($evaluationId, $dimensionType, $dimensionWeight){

		$dimensionData = array(
			'id_evaluation' => $evaluationId,
			'id_dimension_type' => $dimensionType,
			'weight' => $dimensionWeight
		);

		$this->db->insert('evaluation_dimension', $dimensionData);

		$foundEvaluation = $this->getEvaluationDimension($dimensionData);

		if($foundEvaluation !== FALSE){
			$wasAdded = TRUE;
		}else{
			$wasAdded = FALSE;
		}

		return $wasAdded;
	}

	private function getEvaluationDimension($evaluationData){

		$evaluation = $this->db->get_where('evaluation_dimension', $evaluationData)->row_array();

		$evaluation = checkArray($evaluation);

		return $evaluation;
	}

	public function getAllDimensionTypes(){

		$allDimensions = $this->db->get('dimension_type')->result_array();

		$allDimensions = checkArray($allDimensions);

		return $allDimensions;
	}

	public function getDimensionTypesForEvaluation($evaluationId){

		$this->db->select('dimension_type.*');
		$this->db->from('dimension_type');
		$this->db->join("evaluation_dimension", "evaluation_dimension.id_dimension_type = dimension_type.id_dimension_type");
		$this->db->where("evaluation_dimension.id_evaluation", $evaluationId);
		$dimensions = $this->db->get()->result_array();

		$dimensions = checkArray($dimensions);

		return $dimensions;
	}

	public function getDimensionData($evaluationId, $dimensionType){

		$conditions = array(
			'id_evaluation' => $evaluationId,
			'id_dimension_type' => $dimensionType
		);
		
		$dimensionData = $this->db->get_where('evaluation_dimension', $conditions)->row_array();

		$dimensionData = checkArray($dimensionData);

		return $dimensionData;
	}

	public function disableDimension($dimensionId){

		define("NULL_WEIGHT", 0);

		$disabled = $this->changeDimensionWeight($dimensionId, NULL_WEIGHT);

		return $disabled;
	}

	public function updateDimensionWeight($dimensionId, $newWeight){

		$wasChanged = $this->changeDimensionWeight($dimensionId, $newWeight);

		return $wasChanged;
	}

	private function changeDimensionWeight($dimensionId, $newWeight){

		$newDimensionData = array(
			'weight' => $newWeight
		);

		$this->db->where('id_dimension', $dimensionId);
		$this->db->update('evaluation_dimension', $newDimensionData);

		$foundDimensionData = $this->getDimensionDataById($dimensionId);

		if($foundDimensionData !== FALSE){

			if($foundDimensionData['weight'] == $newWeight){
				$wasChanged = TRUE;
			}else{
				$wasChanged = FALSE;
			}

		}else{
			$wasChanged = FALSE;
		}

		return $wasChanged;
	}

	private function getDimensionDataById($dimensionId){
		
		$dimensionData = $this->db->get_where('evaluation_dimension', array('id_dimension' => $dimensionId))->row_array();

		$dimensionData = checkArray($dimensionData);

		return $dimensionData;
	}

	public function checkIfHaveAllDimensions($evaluationId){

		$evaluationDimensions = $this->getEvaluationDimensions($evaluationId);
		$dimensionsTypes = $this->getAllDimensionTypes();

		if($dimensionsTypes !== FALSE){

			$haveAllDimensions = TRUE;
			foreach($evaluationDimensions as $dimension){
				$dimensionOk = FALSE;
				foreach ($dimensionsTypes as $type){
					
					$haveDimension = $dimension['id_dimension_type'] === $type['id_dimension_type'];

					if($haveDimension){
						$dimensionOk = TRUE;
						break;
					}
				}

				if(!$dimensionOk){
					$haveAllDimensions = FALSE;
					break;
				}
			}
		}else{
			$haveAllDimensions = FALSE;
		}

		return $haveAllDimensions;
	}

	public function getEvaluationDimensions($evaluationId){

		$evaluationDimensions = $this->db->get_where('evaluation_dimension', array('id_evaluation' => $evaluationId))->result_array();

		$evaluationDimensions = checkArray($evaluationDimensions);

		return $evaluationDimensions;
	}


	private function getProgramEvaluation($evaluationData){

		$foundEvaluation = $this->db->get_where('program_evaluation', $evaluationData)->row_array();

		$foundEvaluation = checkArray($foundEvaluation);

		return $foundEvaluation;
	}

}