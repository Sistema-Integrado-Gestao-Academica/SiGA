<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once("usuario.php");

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

		$this->loadModel();
		
		$course = $this->input->post('course');
		$student = $this->input->post('student');
		$enrollment = $this->input->post('student_enrollment');

		try{

			$registration = new StudentRegistration($enrollment);

			$this->student_model->saveRegistration($course, $student, $registration);

			$status = "success";
			$message = "Matrícula atualizada com sucesso!";

		}catch(StudentRegistrationException $e){

			$status = "danger";
			$message = $e->getMessage();
		}

		$this->session->set_flashdata($status, $message);
		redirect("student");
	}
}
