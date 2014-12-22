<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once("masterdegree.php");

class Doctorate extends CI_Controller {

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
