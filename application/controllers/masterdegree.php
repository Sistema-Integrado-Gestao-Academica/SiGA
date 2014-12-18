<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH."/exception/CourseNameException.php");
require_once(APPPATH."/exception/CourseException.php");

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

	public function updateMasterDegreeCourse($idCourseToUpdate, $commonAttributes,
								      $specificsAttributes, $secretary){
		try{

			$this->load->model('masterdegree_model');
			$this->masterdegree_model->updateCourseCommonAttributes($idCourseToUpdate, $commonAttributes);

			$specificsAttributes = $this->filterNullAttributes($specificsAttributes);
	
			$this->masterdegree_model->updateCourseSpecificsAttributes($idCourseToUpdate, $specificsAttributes);

			$this->load->model('course_model');
			$this->course_model->updateCourseSecretary($idCourseToUpdate, $secretary);

		}catch(CourseNameException $caughtException){
			throw $caughtException;
		}catch(CourseException $caughtException){
			throw $caughtException;
		}
	}

	private function filterNullAttributes($attributesArray){

		foreach ($attributesArray as $attributeName => $attribute){
			if(empty($attribute)){
				unset($attributesArray[$attributeName]);
			}
		}

		return $attributesArray;
	}

	private function getMasterDegreeForThisCourseId($courseId){
		$this->load->model('masterdegree_model');
		$foundMasterDegree = $this->masterdegree_model->getRegisteredMasterDegreeForThisCourseId($courseId);

		return $foundMasterDegree;
	}

}
