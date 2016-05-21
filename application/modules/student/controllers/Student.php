<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


require_once(MODULESPATH."/auth/constants/GroupConstants.php");
require_once(MODULESPATH."secretary/domain/StudentRegistration.php");
require_once(MODULESPATH."student/domain/Phone.php");
require_once(MODULESPATH."secretary/exception/StudentRegistrationException.php");
require_once(MODULESPATH."student/exception/PhoneException.php");

class Student extends MX_Controller {

	const MODEL_NAME = "student/student_model";

	public function __contruct(){
		parent::__contruct();
		$this->load->model(self::MODEL_NAME);		
	}

	public function index(){

		$session = getSession();
		$loggedUserData = $session->getUserData();
		$userId = $loggedUserData->getId();

		$this->load->model("auth/usuarios_model");
		$userCourses = $this->usuarios_model->getUserCourse($userId);

		$data = array(
			'userData' => $loggedUserData,
			'userCourses' => $userCourses
		);

		loadTemplateSafelyByGroup(GroupConstants::STUDENT_GROUP, 'student/student/student_home', $data);
	}

	public function studentCoursePage($courseId, $userId){

		$this->load->model("auth/usuarios_model");
		$userData = $this->usuarios_model->getUserById($userId);

		$this->load->model("program/course_model");
		$courseData = $this->course_model->getCourseById($courseId);

		$data = array(
			'course' => $courseData,
			'user' => $userData
		);

		loadTemplateSafelyByGroup(GroupConstants::STUDENT_GROUP, 'student/student/student_course_page', $data);
	}

	public function student_offerList($courseId){

		$this->load->model("program/semester_model");
		$currentSemester = $this->semester_model->getCurrentSemester();

		$this->load->model("program/course_model");
		$courseData = $this->course_model->getCourseById($courseId);

		$this->load->module("secretary/offer");
		$offerListDisciplines = $this->offer->getCourseApprovedOfferListDisciplines($courseId, $currentSemester['id_semester']);

		$session = getSession();
		$loggedUserData = $session->getUserData();
		$userId = $loggedUserData->getId();

		$data = array(
			'currentSemester' => $currentSemester,
			'course' => $courseData,
			'offerListDisciplines' => $offerListDisciplines,
			'userId' => $userId
		);

		loadTemplateSafelyByGroup(GroupConstants::STUDENT_GROUP, 'student/student/student_offer_list', $data);
	}

	public function registerEnrollment(){
		
		$course = $this->input->post('course');
		$student = $this->input->post('student');
		$enrollment = $this->input->post('student_enrollment');

		try{

			$registration = new StudentRegistration($enrollment);

			$this->load->model("secretary/enrollment_model");
			$this->enrollment_model->saveRegistration($registration, $course, $student);

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
		
		$session = getSession();
		$userData = $session->getUserData();
		$userId = $userData->getId();
		
		$studentBasicInfo = $this->getBasicInfo($userId);

		$this->load->model("auth/usuarios_model");
		$userStatus = $this->usuarios_model->getUserStatus($userId);
		$userCourses = $this->usuarios_model->getUserCourse($userId);

		$this->load->model("program/semester_model");
		$currentSemester = $this->semester_model->getCurrentSemester();

		$data = array(
			'userData' => $userData,
			'status' => $userStatus,
			'courses' => $userCourses,
			'currentSemester' => $currentSemester,
			'studentData' => $studentBasicInfo
		);

		// On auth_helper
		loadTemplateSafelyByGroup(GroupConstants::STUDENT_GROUP, 'student/student/student_specific_data_form', $data);
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
		
				$this->load->model(self::MODEL_NAME);		
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

		$this->load->model(self::MODEL_NAME);		
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
