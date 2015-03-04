<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once('semester.php');
require_once('temporaryrequest.php');

class Request extends CI_Controller {

	public function studentEnrollment($courseId, $userId){

		$semester = new Semester();
		$currentSemester = $semester->getCurrentSemester();

		$temporaryRequest = new TemporaryRequest();
		$disciplinesToRequest = $temporaryRequest->getUserTempRequest($userId, $courseId, $currentSemester['id_semester']);

		$data = array(
			'semester' => $currentSemester,
			'courseId' => $courseId,
			'userId' => $userId,
			'disciplinesToRequest' => $disciplinesToRequest
		);

		loadTemplateSafelyByGroup("estudante", 'request/enrollment_request', $data);
	}

}
