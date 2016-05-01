<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once("Usuario.php");
require_once("Course.php");
require_once("Semester.php");
require_once("Enrollment.php");

require_once(APPPATH."/constants/GroupConstants.php");

require_once(APPPATH."/data_types/StudentRegistration.php");
require_once(APPPATH."/data_types/basic/Phone.php");

require_once(APPPATH."/controllers/security/session/SessionManager.php");

require_once(APPPATH."/exception/StudentRegistrationException.php");
require_once(APPPATH."/exception/PhoneException.php");

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

		$session = SessionManager::getInstance();
		$loggedUserData = $session->getUserData();
		$userId = $loggedUserData->getId();

		$user = new Usuario();
		$userCourses = $user->getUserCourses($userId);

		$data = array(
			'userData' => $loggedUserData,
			'userCourses' => $userCourses
		);

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
		
		$this->loadModel();

		$session = SessionManager::getInstance();
		$userData = $session->getUserData();
		$userId = $userData->getId();
		
		$studentBasicInfo = $this->getBasicInfo($userId);

		$user = new Usuario();
		$userStatus = $user->getUserStatus($userId);
		$userCourses = $user->getUserCourses($userId);

		$semester = new Semester();
		$currentSemester = $semester->getCurrentSemester();

		$data = array(
			'userData' => $userData,
			'status' => $userStatus,
			'courses' => $userCourses,
			'currentSemester' => $currentSemester,
			'studentData' => $studentBasicInfo
		);

		// On auth_helper
		loadTemplateSafelyByGroup(GroupConstants::STUDENT_GROUP, 'student/student_specific_data_form', $data);
	}

	public function saveBasicInfo(){
		$this->load->library("form_validation");
		$this->form_validation->set_rules("home_phone_number", "Telefone Residencial", "required|alpha_dash");
		$this->form_validation->set_rules("cell_phone_number", "Telefone Celular", "required|alpha_dash");
		$this->form_validation->set_error_delimiters("<p class='alert-danger'>", "</p>");
		$success = $this->form_validation->run();

		if ($success){
			$cellPhone = $this->input->post("cell_phone_number");
			$homePhone = $this->input->post("home_phone_number");
			$idUser = $this->input->post("id_user");

			try{

				$cellPhone = new Phone($cellPhone);
				$homePhone = new Phone($homePhone);

				$studentBasics = array(
					'cell_phone' => $cellPhone,
					'home_phone' => $homePhone,
					'id_user' => $idUser
				);

				$this->loadModel();
				$savedBasicInformation = $this->student_model->updateBasicInfo($studentBasics);

				if($savedBasicInformation){
					$updateStatus = "success";
					$updateMessage = "Novos dados alterados com sucesso";
				}else{
					$updateStatus = "danger";
					$updateMessage = "Não foi possível salvar seus novos dados. Tente novamente.";
				}

			}catch(PhoneException $e){
				$updateStatus = "danger";
				$updateMessage = $e->getMessage();
			}

		} else {
			$updateStatus = "danger";
			$updateMessage = "Não foi possível salvar seus novos dados. Tente novamente.";
		}
		
		$this->session->set_flashdata($updateStatus, $updateMessage);
		redirect("student_information");
	}

	private function getBasicInfo($studentId){

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
