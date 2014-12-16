<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MasterDegree extends CI_Controller {

	public function saveMasterDegreeCourse($commonAttributes, $specificAttributes, $secretary){

		$this->load->model('masterdegree_model');
		$courseId = $this->masterdegree_model->saveCourseCommonAttributes($commonAttributes, $secretary);

		$attributesWasSaved = $this->masterdegree_model->saveCourseSpecificAttributes($courseId, $specificAttributes);

		return $attributesWasSaved;
	}

}
