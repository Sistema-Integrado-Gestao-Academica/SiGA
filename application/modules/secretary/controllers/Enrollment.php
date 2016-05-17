<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH."/data_types/StudentRegistration.php");
require_once(APPPATH."/exception/StudentRegistrationException.php");
require_once(MODULESPATH."/secretary/domain/notification/EnrolledStudentEmail.php");
require_once(MODULESPATH."auth/constants/GroupConstants.php");
require_once(MODULESPATH."auth/constants/PermissionConstants.php");

class Enrollment extends MX_Controller {

	const MODEL_NAME = "secretary/enrollment_model";

	public function __construct(){
		parent::__construct();
		$this->load->model(self::MODEL_NAME);
	}

	/**
	 * Load view to enroll a student
	 * @param $courseId - The id from an active course
	 */
	public function enrollStudentToCourse($courseId){

		$this->load->model("auth/usuarios_model");
		$guests = $this->usuarios_model->getUsersOfGroup(GroupConstants::GUEST_USER_GROUP_ID);

		$this->load->model("program/course_model");
		$foundCourse = $this->course_model->getCourseById($courseId);
		$courseType = $this->course_model->getCourseTypeByCourseId($courseId);

		$data = array(
			'course' => $foundCourse,
			'guests' => $guests
		);

		loadTemplateSafelyByPermission(PermissionConstants::ENROLL_STUDENT_PERMISSION, 'enrollment/enroll_student', $data);
	}

	/**
	 * Enroll a student in a given course
	 * @param $course - The course to enroll the student
	 * @param $user - The student to enroll
	 */
	public function enrollStudent($course, $user){

		$this->load->model("auth/usuarios_model");
		$userForEmail = $this->usuarios_model->getUserById($user);
		$userForEmail = $this->usuarios_model->getUserDataForEmail($userForEmail);

		// Begins a transaction
		$this->db->trans_start();

		$this->enrollment_model->enrollStudentIntoCourse($course, $user);

		$this->addStudentGroupToNewStudent($user);

		// Ends a transaction
		$this->db->trans_complete();

		if($this->db->trans_status() === FALSE){
			$status = "danger";
			$message = "Não foi possível matricular este aluno. Tente novamente.";
		}else{

			log_message("info", "Student ${user} enrolled in course {$course} successfully.");
			$notifyUser = new EnrolledStudentEmail($userForEmail, $course);	
			$notifyUser->notify();

			$status = "success";
			$message = "Aluno matriculado com sucesso.";
		}

		$this->session->set_flashdata($status, $message);
		redirect("secretary/enrollStudent/{$course}");
	}

	public function updateStudentRegistration(){

		$course = $this->input->post("course");
		$student = $this->input->post("student");
		$newRegistration = $this->input->post("new_registration");

		try{
			$registration = new StudentRegistration($newRegistration);

			$this->updateRegistration($registration, $course, $student);

			$status = "success";
			$message = "Matrícula atualizada com sucesso.";

		}catch(StudentRegistrationException $e){
			$status = "danger";
			$message = $e->getMessage();
		}

		$this->session->set_flashdata($status, $message);
		redirect("course/courseStudents/{$course}");
	}

	/**
	 * Updates a registration of a student of a course
	 * @param $registration - StudentRegistration object containing the new registration
	 * @param $course - The course of the student
	 * @param $student - The student to update the registration
	 * @throws StudentRegistrationException
	 */
	public function updateRegistration($registration, $course, $student){

		$this->enrollment_model->saveRegistration($registration, $course, $student);
	}

	/**
	 * Adds the student group to the given user
	 * @param $userId - The user id to add the group
	 */
	private function addStudentGroupToNewStudent($userId){

	  	$group = new Module();

	  	$studentGroup = GroupConstants::STUDENT_GROUP;
		$group->addGroupToUser($studentGroup, $userId);

		$guestGroup = GroupConstants::GUEST_GROUP;
		$group->deleteGroupOfUser($guestGroup, $userId);
	}
}
