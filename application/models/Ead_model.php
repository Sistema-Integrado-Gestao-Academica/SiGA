<?php
require_once(APPPATH."/exception/CourseNameException.php");
class Ead_model extends CI_Model {

	public function saveEad($course_name){

		$this->load->model('course_model');
		$course_id = $this->course_model->getCourseIdByCourseName($course_name);

		$savedEad = $this->db->insert('ead',array('id_course'=>$course_id));

		if($savedEad){
			$insertionStatus = TRUE;
		}else{
			$insertionStatus = FALSE;
		}

		return $insertionStatus;

	}
	
	public function updateEadCourse($idCourse, $courseToUpdate){
		
		try{
			$this->load->model('course_model');
			$this->course_model->updateCourse($idCourse, $courseToUpdate);
		
		}catch(CourseNameException $caughtException){
			throw $caughtException;
		}
		
	}
	
	public function updateEadCourseSecretary($idCourse, $secretaryToUpdate){
		
		$this->load->model('course_model');
		$updatedSecretary = $this->course_model->updateCourseSecretary($idCourse, $secretaryToUpdate);
			
		if($updatedSecretary){
			$returnUpdate = TRUE;
		}else{
			$returnUpdate = FALSE;
		}
			
		return $returnUpdate;
		
	}
}