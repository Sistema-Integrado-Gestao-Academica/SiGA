<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CourseInfo {

	private $id;
	private $name;
	private $programId;
	private $academicSecretaries;
	private $teachers;
	private $researchLines;


	public function __construct($id = FALSE, $name = "", $programId = FALSE, $academicSecretaries = FALSE, 
								$teachers = FALSE, $researchLines = FALSE){

		$this->setId($id);
		$this->setName($name);
		$this->setProgramId($programId);
		$this->setAcademicSecretaries($academicSecretaries);
		$this->setTeachers($teachers);
		$this->setResearchLines($researchLines);		
	}

	private function setId($id){
		$this->id = $id;
	}

	private function setName($name){
		$this->name = $name;
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

	public function getId(){
		return $this->id;
	}

	public function getName(){
		return $this->name;
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