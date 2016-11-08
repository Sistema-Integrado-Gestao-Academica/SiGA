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

		$productions = $this->getCoauthorProductions($userId, $productions);

		if($productions !== FALSE){
			foreach ($productions as $id => $production) {
				$authors = $this->getAuthorsByProductionId($production['id']);
				$production = $this->convertToObject($production, $authors);
				$productions[$id] = $production;
			}
		}

		return $productions;
	}

	public function getProductionById($productionId){

		$foundProduction = $this->getProduction($productionId);

		if($foundProduction !== FALSE){
			foreach ($foundProduction as $id => $production) {
				$production = $this->convertToObject($production);
				$foundProduction[$id] = $production;
			}
		}

		return $foundProduction;
	}

	private function getProduction($productionId){
		$this->db->select("intellectual_production.*");
		$this->db->from("intellectual_production");
		$this->db->where("id", $productionId);

		$foundProduction = $this->db->get()->result_array();

		$foundProduction = checkArray($foundProduction);

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
			'author' => $production->getAuthor(),
			'project' => $production->getProject()
		);

		return $productionArray;
	}

	private function convertToObject($production, $authors = FALSE){

		try{
			$production = new IntellectualProduction($production['author'], $production['title'], $production['type'], $production['year'],
												$production['subtype'], $production['qualis'], $production['periodic'], $production['identifier'], $production['id'], $authors, $production['project']);
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

	public function getCoauthorProductions($userId, $productions){

		$this->db->select("production_id");
		$this->db->from("production_coauthor");
		$this->db->where("user_id", $userId);
		$productionIds = $this->db->get()->result_array();
		$productionIds = checkArray($productionIds);

		if($productionIds !== FALSE){
			foreach ($productionIds as $productionId) {
				$production = $this->getProduction($productionId['production_id']);
				if ($productions !== FALSE){
					array_push($productions, $production[0]);
				}
			}
		}

		return $productions;
	}


	public function saveAuthors($author, $productionId, $order){

		$id = NULL;

		$author_id = $author->getId();
		if($author_id !== FALSE){
			$id = $author_id;
		}

        $data = array(
            'production_id' => $productionId,
            'author_name' => $author->getName(),
            'cpf' => $author->getCpf(),
            'order' => $order,
            'user_id' => $id
        );

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


	public function getAuthorByProductionAndOrder($production, $order){

		$this->db->select("production_coauthor.*");
		$this->db->from("production_coauthor");
		$this->db->where("production_id", $production);
		$this->db->where("order", $order);

		$author = $this->db->get()->result_array();

		$author = checkArray($author);

		return $author;
	}

	public function getAuthorsByProductionId($productionId){

		$this->db->select("author_name, cpf, order");
		$this->db->from("production_coauthor");
		$this->db->order_by("order", "asc");
		$this->db->where("production_id", $productionId);

		$foundAuthors = $this->db->get()->result_array();
		$foundAuthors = checkArray($foundAuthors);

		if($foundAuthors !== FALSE){
			foreach ($foundAuthors as $id => $author) {
				$foundAuthors[$id]['first_author'] = FALSE;
			}
		}
		else{
			$foundAuthors = array();
		}

		$authors = $this->getFirstAuthor($productionId);
		$authors = array_merge($authors, $foundAuthors);

		return $authors;
	}

	private function getFirstAuthor($productionId){

		$production = $this->getProduction($productionId);
		$userId = $production[0]['author'];

		$this->load->model("auth/usuarios_model");
		$user = $this->usuarios_model->getObjectUser($userId);

		$firstAuthor = array(
			'author_name' => $user->getName(),
			'cpf' => $user->getCpf(),
			'order' => 1,
			'first_author' => TRUE
		);

		$authors = array();
		array_push($authors, $firstAuthor);

		return $authors;
	}

	public function	updateCoauthor($productionId, $order, $data){

		$this->db->where('production_id', $productionId);
		$this->db->where('order', $order);

		$updated = $this->db->update('production_coauthor', $data);

		return $updated;
	}

	public function deleteCoauthor($productionId, $order){

		$this->db->where("production_id", $productionId);
		$this->db->where("order", $order);
		$deleted = $this->db->delete('production_coauthor');

		return $deleted;
	}

	public function checkIfOrderExists($order, $productionId){

		$this->db->select("production_coauthor.order");
		$this->db->from("production_coauthor");
		$this->db->where("production_id", $productionId);
		$this->db->where("order", $order);

		$foundOrder = $this->db->get()->result_array();
		$foundOrder = checkArray($foundOrder);

		if($foundOrder !== FALSE){
			$exists = TRUE;
		}
		else{
			$exists = FALSE;
		}

		return $exists;
	}

	public function getProgramsProduction($programs, $year=""){

		if(!empty($programs)){
			$query = "
				SELECT DISTINCT pi.*
				FROM intellectual_production pi, teacher_course tc, course_student cs, course c
				WHERE
				((
					(tc.id_course = c.id_course) 
                 	AND 
                 	(
                     (pi.author = tc.id_user) 
                     	OR 
                      (pi.id IN (SELECT production_id FROM production_coauthor ca, teacher_course tc WHERE ca.user_id = tc.id_user)) 
                    )
				)
				OR
				(
					(cs.id_course = c.id_course) 
                 	AND 
                 	(
                     (pi.author = cs.id_user) 
                     	OR 
                      (pi.id IN (SELECT production_id FROM production_coauthor ca, course_student cs WHERE ca.user_id = cs.id_user)) 
                    )
				))
			";

			if(is_array($programs)){

				if(isset($programs['id_program'])){
					$query .= " AND c.id_program = {$programs['id_program']} ";
				}else{
					$first = TRUE;
					foreach ($programs as $program) {
						$query .= $first
								? "AND (c.id_program = {$program['id_program']}"
								: " OR c.id_program = {$program['id_program']}";
						$first = FALSE;
					}
					$query .= ")";
				}
			}else{
				$query .= " AND c.id_program = {$programs} ";
			}

			if(!empty($year)){

				if(is_array($year)){
					$first = TRUE;
					foreach ($year as $currentYear) {
						$query .= $first
								? "AND (pi.year = {$currentYear}"
								: " OR pi.year = {$currentYear}";
						$first = FALSE;
					}
					$query .= ")";
				}else{
					$query .= " AND pi.year = {$year} ";
				}
			}

			$query .= " ORDER BY pi.year ASC";

			$productions = $this->db->query($query)->result_array();

			$productions = checkArray($productions);
		}else{
			$productions = array();
		}

		return $productions;
	}
}