<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Syllabus extends CI_Controller {

	public function getCourseSyllabus($courseId){
		
		$this->load->model('syllabus_model');
		
		$courseSyllabus = $this->syllabus_model->getCourseSyllabus($courseId);

		return $courseSyllabus;
	}
}
