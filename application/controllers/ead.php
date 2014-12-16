<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ead extends CI_Controller {
	
	public function saveEadCourse($eadCourse,$eadSecretary){
	
		$this->load->model('course_model');
		$savedCourse = $this->course_model->saveCourse($eadCourse);
		$savedSecretary = $this->course_model->saveSecretary($eadSecretary,$eadCourse['course_name']);
		$this->load->model('ead_model');
		$savedEad = $this->ead_model->saveead($eadCourse['course_name']);
	
		if($savedCourse && $savedSecretary && $savedEad){
			$insertionStatus = TRUE;
		}else{
			$insertionStatus = FALSE;
		}
	
		return $insertionStatus;
	
	}
	
}
