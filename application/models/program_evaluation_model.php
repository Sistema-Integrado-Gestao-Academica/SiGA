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

	public function addDimensionTypeToEvaluation($evaluationId, $dimensionType){

		$dimensionData = array(
			'id_evaluation' => $evaluationId,
			'id_dimension_type' => $dimensionType
		);

		$this->db->insert('evaluation_dimension', $dimensionData);
	}

	public function getAllDimensionTypes(){

		$allDimensions = $this->db->get('dimension_type')->result_array();

		$allDimensions = checkArray($allDimensions);

		return $allDimensions;
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

	private function getEvaluationDimensions($evaluationId){

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