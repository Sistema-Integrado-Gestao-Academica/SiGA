<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once("Portal.php");

class CourseInfo extends Portal{

	private $id;
	private $name;
	private $programId;
	private $academicSecretaries;
	private $teachers;
	private $researchLines;


	public function __construct($id = FALSE, $name = "", $programId = FALSE, $academicSecretaries = FALSE,
								$teachers = FALSE, $researchLines = FALSE){

		parent::__construct($id, $name);
		$this->setProgramId($programId);
		$this->setAcademicSecretaries($academicSecretaries);
		$this->setTeachers($teachers);
		$this->setResearchLines($researchLines);
	}

	private function setProgramId($programId){
		$this->programId = $programId;
	}

	private function setAcademicSecretaries($academicSecretaries){
		$this->academicSecretaries = $academicSecretaries;
	}

	private function setResearchLines($researchLines){
		$this->researchLines = $researchLines;
	}

	private function setTeachers($teachers){
		$this->teachers = $teachers;
	}

	public function getProgramId(){
		return $this->programId;
	}

	public function getAcademicSecretaries(){
		return $this->academicSecretaries;
	}

	public function getResearchLines(){
		return $this->researchLines;
	}

	public function getTeachers(){
		return $this->teachers;
	}

}