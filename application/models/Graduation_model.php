<?php
require_once(APPPATH."/exception/CourseNameException.php");
class Graduation_model extends CI_Model {
	
	public function saveGraduation($course_name){
		
		$this->load->model('course_model');
		$course_id = $this->course_model->getCourseIdByCourseName($course_name);
		
		$savedGraduation = $this->db->insert('graduation',array('id_course'=>$course_id));
		
		if($savedGraduation){
			$insertionStatus = TRUE;
		}else{
			$insertionStatus = FALSE;
		}
		
		return $insertionStatus;
		
	}
	
	public function updateGraduationCourse($idCourse, $courseToUpdate){
		
		try{
			$this->load->model('course_model');
			$this->course_model->updateCourse($idCourse, $courseToUpdate);
				
		}catch(CourseNameException $caughtException){
			throw $caughtException;
		}
		
	}
	
	public function updateGraduationCourseSecretary($idCourse, $secretaryToUpdate){
		
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