<?php

require_once(MODULESPATH."program/domain/intellectual_production/Intellectualproduction.php");

class Production_model extends CI_Model {

	public function createProduction($production){
		
		$production = $this->convertToArray($production);

		$success = $this->db->insert('intellectual_production', $production);
		
		return $success;
	}

	public function getUserProductions($userId){

		$this->db->select("intellectual_production.*");
		$this->db->from("intellectual_production");
		$this->db->where("author", $userId);

		$productions = $this->db->get()->result_array();

		$productions = checkArray($productions);

		if($productions !== FALSE){
			foreach ($productions as $id => $production) {
				$production = $this->convertToObject($production);
				$productions[$id] = $production;
			}
		}

		return $productions;
	}


	private function convertToArray($production){


		$productionArray = array(
			'type' => $production->getType(),
			'subtype' => $production->getSubtype(),
			'title' => $production->getTitle(),
			'year' => $production->getYear(),
			'periodic' => $production->getPeriodic(),
			'qualis' => $production->getQualis(),
			'identifier' => $production->getIdentifier(),
			'author' => $production->getAuthor()
		);

		return $productionArray;
	}

	private function convertToObject($production){

		try{
			$production = new IntellectualProduction($production['author'], $production['title'], $production['type'], $production['year'],
												$production['subtype'], $production['qualis'], $production['periodic'], $production['identifier'], $production['id']);
		}
		catch(IntellectualProductionException $exception){
			$production = FALSE;
		}

		return $production;
	}

}