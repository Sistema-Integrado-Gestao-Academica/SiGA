<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once("Portal.php");

class ProgramInfo extends Portal{

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

		parent::__construct($id, $name);
		$this->setAcronym($acronym);
		$this->setCoordinatorId($coordinatorId);
		$this->setHistory($history);
		$this->setContact($contact);
		$this->setSummary($summary);
		$this->setResearchLine($researchLine);
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