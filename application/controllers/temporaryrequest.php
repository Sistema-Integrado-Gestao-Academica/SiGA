<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * In this class, consider where has been written 'temp' equals to 'temporary'
 */
class TemporaryRequest extends CI_Controller {

	public function getUserTempRequest($userId, $courseId, $semesterId){

		$this->load->model('temporaryrequest_model');

		$request = $this->temporaryrequest_model->getUserTempRequest($userId, $courseId, $semesterId);

		return $request;
	}

}
