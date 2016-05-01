<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ead extends CI_Controller {
	
	public function saveEadCourse($eadCourse,$eadSecretary){
	
		$this->load->model('course_model');
		$savedCourse = $this->course_model->saveCourse($eadCourse);
		/**
		 * DEPRECATED CODE
		 * $savedSecretary = $this->course_model->saveSecretary($eadSecretary,$eadCourse['course_name']);
		 */
		$this->load->model('ead_model');
		$savedEad = $this->ead_model->saveEad($eadCourse['course_name']);
	
		if($savedCourse && $savedEad){
			$insertionStatus = TRUE;
		}else{
			$insertionStatus = FALSE;
		}
	
		return $insertionStatus;
	
	}

	public function updateEadCourse($idCourse, $courseToUpdate,$secretaryToUpdate){
		try{
			$this->load->model('ead_model');
			$this->ead_model->updateEadCourse($idCourse, $courseToUpdate);
			$this->ead_model->updateEadCourseSecretary($idCourse, $secretaryToUpdate);
				
		}catch(CourseNameException $caughtException){
			throw $caughtException;
		}
	}
}
