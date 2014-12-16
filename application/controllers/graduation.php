<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Graduation extends CI_Controller {

	public function saveGraduationCourse($graduationCourse,$graduationSecretary){

		$this->load->model('course_model');
		$savedCourse = $this->course_model->saveCourse($graduationCourse);
		$savedSecretary = $this->course_model->saveSecretary($graduationSecretary,$graduationCourse['course_name']);
// 		$this->load->model('graduation_model');
// 		$savedGraduation = $this->graduation_model->saveGraduation($graduationCourse['course_name']);
		
		if($savedCourse && $savedSecretary){
			$insertionStatus = TRUE;
		}else{
			$insertionStatus = FALSE;
		}
		
		return $insertionStatus;
		
	}
}
