<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class TeacherInfo {

	private $id;
	private $name;
	private $email;
	private $summary;
	private $latteslink;
	private $researchLine;


	public function __construct($id = FALSE, $name = "", $email = FALSE, 
								$summary = FALSE, $latteslink = FALSE, $researchLine = FALSE){

		$this->setId($id);
		$this->setName($name);
		$this->setEmail($email);
		$this->setSummary($summary);
		$this->setLattesLink($latteslink);
		$this->setResearchLine($researchLine);		

	}

	private function setId($id){
		$this->id = $id;
	}

	private function setName($name){
		$this->name = $name;
	}

	private function setEmail($email){
		$this->email = $email;
	}

	private function setLattesLink($latteslink){
		$this->latteslink = $latteslink;
	}

	private function setResearchLine($researchLine){
		$this->researchLine = $researchLine;
	}
	
	private function setSummary($summary){
		$this->summary = $summary;
	}

	public function getId(){
		return $this->id;
	}

	public function getName(){
		return $this->name;
	}
	
	public function getEmail(){
		return $this->email;
	}
	
	public function getLattesLink(){
		return $this->latteslink;
	}
		
	public function getSummary(){
		return $this->summary;
	}

	public function getResearchLine(){
		return $this->researchLine;
	}
	
	
}