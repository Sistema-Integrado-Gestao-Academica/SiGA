<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(MODULESPATH."/secretary/domain/StudentRegistration.php");
require_once(MODULESPATH."/secretary/exception/StudentRegistrationException.php");

require_once(MODULESPATH."/notification/domain/emails/EnrolledStudentEmail.php");
require_once(MODULESPATH."/notification/domain/emails/UnknownUserEmail.php");

require_once(MODULESPATH."secretary/constants/EnrollmentConstants.php");
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
		$courseGuests = $this->usuarios_model->getCourseGuests($courseId);

		$this->load->model("program/course_model");
		$foundCourse = $this->course_model->getCourseById($courseId);
		$courseType = $this->course_model->getCourseTypeByCourseId($courseId);

		$data = array(
			'course' => $foundCourse,
			'courseGuests' => $courseGuests
		);

		loadTemplateSafelyByPermission(PermissionConstants::ENROLL_STUDENT_PERMISSION, 'secretary/enrollment/enroll_student', $data);
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

		$success = $this->enrollment_model->enrollStudentIntoCourse($course, $user);

		if($success){
			$this->usuarios_model->deleteUserFromCourseGuest($user);
			$this->addStudentGroupToNewStudent($user);
		}

		// Ends a transaction
		$this->db->trans_complete();

		if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();

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
		redirect("program/course/courseStudents/{$course}");
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

	  	$this->load->module("auth/module");

	  	$studentGroup = GroupConstants::STUDENT_GROUP;
		$this->module->addGroupToUser($studentGroup, $userId);

		$guestGroup = GroupConstants::GUEST_GROUP;
		$this->module->deleteGroupOfUser($guestGroup, $userId);
	}

	public function newCourseForGuest(){

        $courseId = $this->input->post("courses_name");
        $session = getSession();
        $user = $session->getUserData();
        $userId = $user->getId();

		$this->load->model("auth/usuarios_model");
        $success = $this->usuarios_model->addCourseToGuest($userId, $courseId);

        if($success){
            $session->showFlashMessage("success", "Inscrição solicitada com sucesso!");
            redirect("/");
        }
        else{
            $session->showFlashMessage("danger", "Não foi possível solicitar sua inscrição. Tente novamente!");
            redirect("guest_home");
        }
    }

    public function setUserAsUnknown(){

        $userId = $this->input->post("user");
        $courseId = $this->input->post("course");

        $this->load->model("auth/usuarios_model");

        $success = $this->usuarios_model->updateCourseGuest($userId, $courseId, EnrollmentConstants::UNKNOWN_STATUS);

        if($success){
        	$user = $this->usuarios_model->getObjectUser($userId);
        	$email = new UnknownUserEmail($user, $courseId);
			$success = $email->notify();
        }
        else{
        	$session = getSession();
        	$session->showFlashMessage("danger", "Não foi possível marcar o aluno como desconhecido");
        }

        // Refresh page
        $this->enrollStudentToCourse($courseId);
    }

    public function showEnrollmentReport(){

    	// Get the current user name
		$session = getSession();
		$loggedUserData = $session->getUserData();
		$userName = $loggedUserData->getName();

		$this->load->module("secretary/secretary");
		$programs = $this->secretary->getSecretaryPrograms();

    	$data = array(
    		'userName' => $userName,
    		'programs' => $programs
    	);
    	loadTemplateSafelyByGroup(GroupConstants::SECRETARY_GROUP, "secretary/enrollment/enrollment_report", $data);
    }

    public function programEnrollmentReport($programId){

		$session = getSession();
    	$user = $session->getUserData();
    	$userId = $user->getId();

		// Get the program courses 
		$courses = $this->enrollment_model->getProgramCoursesOfSecretary($programId, $userId);

    	$this->load->model("program/semester_model");
    	$semester = $this->semester_model->getCurrentSemester();
    	$semesterId = $semester['id_semester'];
    	
		$programDisciplines = array();
		$programDisciplinesClasses = array();
		$enrolledStudents = array();

    	if($courses !== FALSE){

			$this->load->module("secretary/offer");	
    		foreach ($courses as $course) {
    			$courseId = $course['id_course'];

		    	$offer = $this->offer->getOfferBySemesterAndCourse($semesterId, $courseId);

		    	$disciplines = $this->offer->getCourseApprovedOfferListDisciplines($courseId, $semesterId);
		    	$disciplinesClasses = $this->getDisciplinesClasses($disciplines, $offer['id_offer']);
		    	$students = $this->getStudentsPerClass($disciplinesClasses);

		    	if($disciplinesClasses !== FALSE){

		    		$programDisciplinesClasses = ($programDisciplinesClasses + $disciplinesClasses);
		    	}
		    	if($disciplines !== FALSE){

		    		$programDisciplines = ($programDisciplines + $disciplines);
		    	}
		    	if($students !== FALSE){

		    		$enrolledStudents = ($enrolledStudents + $students);
		    	}
    		}

    	}
    	// Used to show all students
    	$offerDisciplinesIds = $this->getStringOfOfferDisciplinesIds($programDisciplinesClasses);

    	$data = array(
    		'disciplines' => $programDisciplines,
    		'disciplinesClasses' => $programDisciplinesClasses,
    		'students' => $enrolledStudents,
    		'semester' => $semester,
    		'offerDisciplinesIds' => $offerDisciplinesIds
    	);


    	loadTemplateSafelyByGroup(GroupConstants::SECRETARY_GROUP, "secretary/enrollment/program_enrollment_report", $data);
    }

    private function getDisciplinesClasses($disciplines, $offerId){

    	$disciplinesClasses = array();
    	if($disciplines !== FALSE){

	    	foreach ($disciplines as $discipline) {
	    		$id = $discipline['discipline_code'];
	    		$classes = $this->offer_model->getOfferDisciplineClasses($id, $offerId);
	    		$disciplinesClasses[$id] = $classes;
	    	}
    	}

    	return $disciplinesClasses;
    }

    private function getStudentsPerClass($disciplinesClasses){

    	$classStudents = array();
    	$this->load->model("secretary/request_model");
    	if($disciplinesClasses !== FALSE){
    		foreach ($disciplinesClasses as $classes) {

    			if($classes !== FALSE){

	    			foreach ($classes as $class) {
		    			$idOfferDiscipline = $class['id_offer_discipline'];
				    	$students = $this->request_model->getStudentsEnrolledByClass($idOfferDiscipline);
	    				sort($students);
				    	$classStudents[$idOfferDiscipline] = $students;
	    			}
    			}
    		}
    	}

    	return $classStudents;
    }

    private function getStringOfOfferDisciplinesIds($programDisciplinesClasses){

    	$offerDisciplinesIds = array();
    	if($programDisciplinesClasses !== FALSE && !empty($programDisciplinesClasses)){
    		foreach ($programDisciplinesClasses as $disciplinesClass) {
    			if($disciplinesClass !== FALSE){
	    			foreach ($disciplinesClass as $class) {
		    			array_push($offerDisciplinesIds, $class['id_offer_discipline']);
	    			}
    			}
    		}
    	}

    	$ids = implode(",", $offerDisciplinesIds);
    	return $ids;
    }
}
