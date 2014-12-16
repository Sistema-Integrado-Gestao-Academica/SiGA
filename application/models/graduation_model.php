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
}