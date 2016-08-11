<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ProgramInfo {

	private $id;
	private $name;
	private $acronym;
	private $coordinatorId;
	private $contact;
	private $history;
	private $summary;
	private $researchLine;
	private $courses; 
	private $coordinatorData;


	public function __construct($id = FALSE, $name = "", $acronym = FALSE, 
								$coordinatorId = FALSE, $contact = FALSE, 
								$history = FALSE, $summary = FALSE, $researchLine = FALSE, $courses = FALSE, $coordinatorData = FALSE){

		$this->setId($id);
		$this->setName($name);
		$this->setAcronym($acronym);
		$this->setCoordinatorId($coordinatorId);
		$this->setHistory($history);
		$this->setContact($contact);
		$this->setSummary($summary);
		$this->setResearchLine($researchLine);
	}

	private function setId($id){
		$this->id = $id;
	}

	private function setName($name){
		$this->name = $name;
	}

	private function setAcronym($acronym){
		$this->acronym = $acronym;
	}

	private function setCoordinatorId($coordinatorId){
		$this->coordinatorId = $coordinatorId;
	}

	private function setContact($contact){
		$this->contact = $contact;
	}

	private function setHistory($history){
		$this->history = $history;
	}

	private function setSummary($summary){
		$this->summary = $summary;
	}

	private function setResearchLine($researchLine){
		$this->researchLine = $researchLine;
	}

	public function setCourses($courses){
		$this->courses = $courses;
	}

	public function setCoordinatorData($coordinatorData){
		$this->coordinatorData = $coordinatorData;
	}

	public function getId(){
		return $this->id;
	}

	public function getName(){
		return $this->name;
	}
	
	public function getCoordinatorId(){
		return $this->coordinatorId;
	}
	
	public function getAcronym(){
		return $this->acronym;
	}
	
	public function getHistory(){
		return $this->history;
	}
	
	public function getResearchLine(){
		return $this->researchLine;
	}
	
	public function getContact(){
		return $this->contact;
	}

	public function getSummary(){
		return $this->summary;
	}

	public function getCourses(){
		return $this->courses;
	}

	public function getCoordinatorData(){
		return $this->coordinatorData;
	}
}