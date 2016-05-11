<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Graduation extends CI_Controller {

	public function saveGraduationCourse($graduationCourse){

		$this->load->model('course_model');
		$savedCourse = $this->course_model->saveCourse($graduationCourse);
		/**
		 * DEPRECATED CODE
		 * $savedSecretary = $this->course_model->saveSecretary($graduationSecretary,$graduationCourse['course_name']);
		 */
 		$this->load->model('graduation_model');
 		$savedGraduation = $this->graduation_model->saveGraduation($graduationCourse['course_name']);
		
		if($savedCourse && $savedGraduation){
			$insertionStatus = TRUE;
		}else{
			$insertionStatus = FALSE;
		}
		
		return $insertionStatus;
		
	}
	
	public function updateGraduationCourse($idCourse, $courseToUpdate,$secretaryToUpdate){
		
		try{
			$this->load->model('graduation_model');
			$this->graduation_model->updateGraduationCourse($idCourse, $courseToUpdate);
			$this->graduation_model->updateGraduationCourseSecretary($idCourse, $secretaryToUpdate);
			
		}catch(CourseNameException $caughtException){
			throw $caughtException;
		}
	}
}
