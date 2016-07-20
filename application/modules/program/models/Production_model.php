<?php

require_once(MODULESPATH."program/domain/intellectual_production/Intellectualproduction.php");

class Production_model extends CI_Model {

	public function createProduction($production){
		
		$production = $this->convertToArray($production);

		$success = $this->db->insert('intellectual_production', $production);
		
		return $success;
	}

	public function updateProduction($production){
		
		$this->db->where('id', $production->getId());
		$data = $this->convertToArray($production);
		
		$updated = $this->db->update('intellectual_production', $data);
		
		return $updated;
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

	public function getProductionById($productionId){

		$this->db->select("intellectual_production.*");
		$this->db->from("intellectual_production");
		$this->db->where("id", $productionId);

		$foundProduction = $this->db->get()->result_array();

		$foundProduction = checkArray($foundProduction);

		if($foundProduction !== FALSE){
			foreach ($foundProduction as $id => $production) {
				$production = $this->convertToObject($production);
				$foundProduction[$id] = $production;
			}
		}

		return $foundProduction;
	}

	public function deleteProduction($id) {
		
		$this->db->where('id', $id);
		$deleted = $this->db->delete('intellectual_production');
		
		return $deleted;
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

	public function getQualisByPeriodicName($periodic){

		$this->db->select("qualis, issn");
		$this->db->from("periodic_qualis");
		$this->db->where("periodic", $periodic);
		$qualis = $this->db->get()->result_array();

		$qualis = checkArray($qualis);

		return $qualis;
	}

	public function getQualisByISSN($issn){

		$this->db->select("periodic, qualis");
		$this->db->from("periodic_qualis");
		$this->db->where("issn", $issn);
		$qualis = $this->db->get()->result_array();

		$qualis = checkArray($qualis);

		return $qualis;
	}

	public function getLastProduction($production){
		
		$query = $this->db->query("SELECT MAX(id) FROM intellectual_production");
		$row = $query->row_array();
	    $lastId = $row["MAX(id)"];

	    $intellectual_production = $this->getProductionById($lastId);

		if($intellectual_production[0]->getTitle() != $production->getTitle()){
			$lastId = FALSE;
		}

		return $lastId;
	}


	public function saveAuthors($data){

		$success = $this->db->insert('production_coauthor', $data);
		
		return $success;
	}

	public function getAuthorByProductionAndName($production, $name){

		$this->db->select("production_coauthor.*");
		$this->db->from("production_coauthor");
		$this->db->where("production_id", $production);
		$this->db->where("author_name", $name);
		
		$author = $this->db->get()->result_array();

		$author = checkArray($author);

		return $author;
	}
}