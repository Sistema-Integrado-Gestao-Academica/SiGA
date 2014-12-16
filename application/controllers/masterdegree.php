<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MasterDegree extends CI_Controller {

	public function saveMasterDegreeCourse($commonAttributes, $specificAttributes){
		$courseId = $this->saveCourseCommonAttributes($commonAttributes);

		$this->saveCourseSpecificAttributes($courseId, $specificAttributes);
	}

	private function saveCourseSpecificAttributes($courseId, $specificAttributes){

		$course_id = array('id_course' => $courseId);

		$attributes = array_merge($course_id, $specificAttributes);

		$this->db->insert('academic_program', $specificAttributes);
	}

	private function saveCourseCommonAttributes($commonAttributes){
		$this->db->insert('course', $commonAttributes);

		$courseName = $commonAttributes['course_name'];
		
		$insertedCourseId = $this->getSavedCourseIdByCourseName($courseName);

		return $insertedCourseId;
	}

	private function getSavedCourseIdByCourseName($courseName){
		$this->db->select('id_course');
		$searchResult = $this->db->get_where('course', array('course_name' => $courseName));
		$searchResult = $searchResult->row_array();

		$courseId = $searchResult['id_course'];

		return $courseId;
	}
}
