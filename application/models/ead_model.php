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
}