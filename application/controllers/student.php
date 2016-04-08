<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once("usuario.php");
require_once("course.php");
require_once("semester.php");
require_once("enrollment.php");

require_once(APPPATH."/constants/GroupConstants.php");
require_once(APPPATH."/data_types/StudentRegistration.php");
require_once(APPPATH."/exception/StudentRegistrationException.php");

class Student extends CI_Controller {

	const MODEL_NAME = "student_model";

	public function __contruct(){
		parent::__contruct();
		$this->loadModel();
	}

	public function loadModel(){
		$this->load->model(self::MODEL_NAME);		
	}

	public function index(){

		$loggedUserData = $this->session->userdata("current_user");
		$userId = $loggedUserData['user']['id'];

		$user = new Usuario();
		$userCourses = $user->getUserCourses($userId);

		$data = array(
			'userData' => $loggedUserData['user'],
			'userCourses' => $userCourses
		);

		// On auth_helper
		loadTemplateSafelyByGroup(GroupConstants::STUDENT_GROUP, 'student/student_home', $data);
	}

	public function registerEnrollment(){
		
		$course = $this->input->post('course');
		$student = $this->input->post('student');
		$enrollment = $this->input->post('student_enrollment');

		try{

			$registration = new StudentRegistration($enrollment);

			$enrollment = new Enrollment();
			$enrollment->updateRegistration($registration, $course, $student);

			$status = "success";
			$message = "Matrícula atualizada com sucesso!";

		}catch(StudentRegistrationException $e){

			$status = "danger";
			$message = $e->getMessage();
		}

		$this->session->set_flashdata($status, $message);
		redirect("student");
	}

	public function studentInformation(){
		$loggedUserData = $this->session->userdata("current_user");
		$userId = $loggedUserData['user']['id'];
		
		$studentBasicInfo = $this->getBasicInfo($userId);

		$user = new Usuario();
		$userStatus = $user->getUserStatus($userId);
		$userCourses = $user->getUserCourses($userId);

		$semester = new Semester();
		$currentSemester = $semester->getCurrentSemester();

		$data = array(
			'userData' => $loggedUserData['user'],
			'status' => $userStatus,
			'courses' => $userCourses,
			'currentSemester' => $currentSemester,
			'studentData' => $studentBasicInfo
		);

		// On auth_helper
		loadTemplateSafelyByGroup(GroupConstants::STUDENT_GROUP, 'student/student_specific_data_form', $data);
	}

	private function getBasicInfo($studentId){

		$this->loadModel();
		$basicInfo = $this->student_model->getBasicInfo($studentId);

		if($basicInfo !== FALSE){

			// Get the first element, because this data will be the same to all results.
			$studentInfo = $basicInfo[0];
			$homePhone = $studentInfo["home_phone"];
			$cellPhone = $studentInfo["cell_phone"];
			$email = $studentInfo["email"];

			$studentBasicInfo["home_phone"] = $homePhone;
			$studentBasicInfo["cell_phone"] = $cellPhone;
			$studentBasicInfo["email"] = $email;
			
			$course = new Course();
			foreach($basicInfo as $info){
				$courseId = $info["id_course"];
				$courseData = $course->getCourseById($courseId);
				$studentBasicInfo["enrollment"][$courseId] = "Curso <b>"
																.$courseData["course_name"]
																."</b> - ".$info["enrollment"];
			}


		}else{
			$studentBasicInfo = FALSE;
		}

		return $studentBasicInfo;
	}
}
