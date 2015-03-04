<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once('semester.php');

class Request extends CI_Controller {

	public function studentEnrollment($courseId, $userId){

		$semester = new Semester();
		$currentSemester = $semester->getCurrentSemester();

		$disciplinesToRequest = FALSE;

		$data = array(
			'semester' => $currentSemester,
			'courseId' => $courseId,
			'userId' => $userId,
			'disciplinesToRequest' => $disciplinesToRequest
		);

		loadTemplateSafelyByGroup("estudante", 'request/enrollment_request', $data);
	}

}
