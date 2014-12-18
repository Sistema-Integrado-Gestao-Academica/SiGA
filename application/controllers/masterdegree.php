<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MasterDegree extends CI_Controller {

	public function saveMasterDegreeCourse($commonAttributes, $specificAttributes, $secretary){

		$this->load->model('masterdegree_model');
		$courseId = $this->masterdegree_model->saveCourseCommonAttributes($commonAttributes, $secretary);

		$attributesWasSaved = $this->masterdegree_model->saveCourseSpecificAttributes($courseId, $specificAttributes);

		return $attributesWasSaved;
	}

	public function getMasterDegreeByCourseId($courseId){
		
		// Validate $courseId
		
		$masterDegree = $this->getMasterDegreeForThisCourseId($courseId);

		return $masterDegree;
	}

	private function getMasterDegreeForThisCourseId($courseId){
		$this->load->model('masterdegree_model');
		$foundMasterDegree = $this->masterdegree_model->getRegisteredMasterDegreeForThisCourseId($courseId);

		return $foundMasterDegree;
	}

}
