<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH."/controllers/course.php");

class Syllabus_model extends CI_Model {

	public function getCourseSyllabus($courseId){

		$foundSyllabus = $this->getSyllabusByCourseId($courseId);

		return $foundSyllabus;
	}

	public function newSyllabus($courseId){

		$course = new Course();

		$courseExists = $course->checkIfCourseExists($courseId);

		if($courseExists){

			$syllabus = array(
				'id_course' => $courseId
			);
			$this->saveNewSyllabus($syllabus);

			$foundSyllabus = $this->getSyllabusByCourseId($courseId);

			if($foundSyllabus !== FALSE){
				$wasSaved = TRUE;
			}else{
				$wasSaved = FALSE;
			}


		}else{
			$wasSaved = FALSE;
		}

		return $wasSaved;
	}

	private function getSyllabusByCourseId($courseId){

		$searchResult = $this->db->get_where('course_syllabus', array('id_course' => $courseId));
		$foundSyllabus = $searchResult->row_array();

		if(sizeof($foundSyllabus) > 0){
			// Nothing to do
		}else{
			$foundSyllabus = FALSE;
		}

		return $foundSyllabus;
	}

	private function saveNewSyllabus($syllabus){

		$this->db->insert('course_syllabus', $syllabus);
	}

}