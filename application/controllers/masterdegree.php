<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH."/exception/CourseNameException.php");
require_once(APPPATH."/exception/CourseException.php");

class MasterDegree extends CI_Controller {

	public function saveMasterDegreeAcademicCourse($commonAttributes, $specificAttributes, $secretary){

		$this->load->model('masterdegree_model');
		$courseId = $this->masterdegree_model->saveCourseCommonAttributes($commonAttributes, $secretary);

		$attributesWasSaved = $this->masterdegree_model->saveAcademicCourseSpecificAttributes($courseId, $specificAttributes);

		return $attributesWasSaved;
	}
	
	public function saveMasterDegreeProfessionalCourse($commonAttributes, $specificAttributes, $secretary){
	
		$this->load->model('masterdegree_model');
		$courseId = $this->masterdegree_model->saveCourseCommonAttributes($commonAttributes, $secretary);
	
		$attributesWasSaved = $this->masterdegree_model->saveProfessionalCourseSpecificAttributes($courseId, $specificAttributes);
	
		return $attributesWasSaved;
	}
	
	public function getMasterDegreeByCourseId($courseId){
		
		// Validate $courseId
		
		$masterDegree = $this->getMasterDegreeForThisCourseId($courseId);

		return $masterDegree;
	}

	public function updateMasterDegreeAcademicCourse($idCourseToUpdate, $commonAttributes,
								      $specificsAttributes, $secretary){
		try{

			$this->load->model('masterdegree_model');
			$this->masterdegree_model->updateCourseCommonAttributes($idCourseToUpdate, $commonAttributes);

			$specificsAttributes = $this->filterNullAttributes($specificsAttributes);
	
			$this->masterdegree_model->updateAcademicCourseSpecificsAttributes($idCourseToUpdate, $specificsAttributes);

			$this->load->model('course_model');
			$this->course_model->updateCourseSecretary($idCourseToUpdate, $secretary);

		}catch(CourseNameException $caughtException){
			throw $caughtException;
		}catch(CourseException $caughtException){
			throw $caughtException;
		}
	}
	
	public function updateMasterDegreeProfessionalCourse($idCourseToUpdate, $commonAttributes,
			$specificsAttributes, $secretary){
		try{
	
			$this->load->model('masterdegree_model');
			$this->masterdegree_model->updateCourseCommonAttributes($idCourseToUpdate, $commonAttributes);
	
			$specificsAttributes = $this->filterNullAttributes($specificsAttributes);
	
			$this->masterdegree_model->updateProfessionalCourseSpecificsAttributes($idCourseToUpdate, $specificsAttributes);
	
			$this->load->model('course_model');
			$this->course_model->updateCourseSecretary($idCourseToUpdate, $secretary);
	
		}catch(CourseNameException $caughtException){
			throw $caughtException;
		}catch(CourseException $caughtException){
			throw $caughtException;
		}
	}
	
	public function checkIfExistsMasterDegreeForThisCourse($courseId){
		$this->load->model('masterdegree_model');
		$thereIsMasterDegree = $this->masterdegree_model->checkIfExistsMasterDegreForThisCourse($courseId);
		return $thereIsMasterDegree;
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
