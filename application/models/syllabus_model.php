<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Syllabus_model extends CI_Model {

	public function getCourseSyllabus($courseId){

		$searchResult = $this->db->get_where('course_syllabus', array('id_course' => $courseId));
		$foundSyllabus = $searchResult->row_array();

		if(sizeof($foundSyllabus) > 0){
			// Nothing to do
		}else{
			$foundSyllabus = FALSE;
		}

		return $foundSyllabus;
	}
}
