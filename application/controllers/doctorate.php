<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once("masterdegree.php");
require_once(APPPATH."/exception/DoctorateException.php");

class Doctorate extends CI_Controller {

	/**
	* Save a doctorate course on database
	* @param $programId - Course id to associate the doctorate
	* @param $doctorateAttributes - Array with the attributes of the doctorate to be saved
	* @return
	*/
	public function saveDoctorate($programId, $doctorateAttributes){

		// Validates params

		$this->load->model('doctorate_model');
		$this->doctorate_model->saveDoctorate($programId, $doctorateAttributes);
	}

	public function deleteDoctorate($courseId){
		
		$this->load->model('doctorate_model');
		$this->doctorate_model->deleteDoctorateByCourseId($courseId);
	}

	public function getRegisteredDoctorateForCourse($courseId){

		// Validate $courseId

		$this->load->model('doctorate_model');

		$foundDoctorate = $this->doctorate_model->getRegisteredDoctorateForCourse($courseId);

		return $foundDoctorate;
	}

	public function checkIfHaveMasterDegree($courseId){
		$masterDegree = new MasterDegree();
		$thereIsMasterDegree = $masterDegree->checkIfExistsAcademicMasterDegreeForThisCourse($courseId);

		return $thereIsMasterDegree;
	}



}
