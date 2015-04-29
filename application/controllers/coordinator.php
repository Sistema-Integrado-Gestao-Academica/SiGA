<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once("program.php");
require_once("course.php");

class Coordinator extends CI_Controller {

	public function index(){

		$loggedUser = $this->session->userdata("current_user");
		$user = $loggedUser['user'];
		$userId = $user['id'];

		$program = new Program();
		$coordinatorPrograms = $program->getCoordinatorPrograms($userId);

		$data = array(
			'coordinatorPrograms' => $coordinatorPrograms,
			'user' => $user
		);

		loadTemplateSafelyByGroup("coordenador",'program/coordinator_programs', $data);
	}

	public function displayProgramCourses($programId){

		$program = new Program();

		$programCourses = $program->getProgramCourses($programId);
		$programData = $program->getProgramById($programId);

		$data = array(
			'programCourses' => $programCourses,
			'program' => $programData
		);

		loadTemplateSafelyByGroup("coordenador",'program/coordinator_program_courses', $data);
	}

	public function displayCourseStudents($courseId){

		$course = new Course();

		$courseStudents = $course->getCourseStudents($courseId);
		$courseData = $course->getCourseById($courseId);

		$data = array(
			'courseStudents' => $courseStudents,
			'course' => $courseData
		);

		loadTemplateSafelyByGroup("coordenador",'program/coordinator_course_students', $data);	
	}
}