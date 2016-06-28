<?php

class Production_model extends CI_Model {

	public function createProduction($production){
		
		$production = $this->convertToArray($production);

		$success = $this->db->insert('intellectual_production', $production);
		
		return $success;
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

}