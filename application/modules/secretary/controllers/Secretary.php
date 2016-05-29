<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(MODULESPATH."program/controllers/Course.php");
require_once(MODULESPATH."/auth/constants/PermissionConstants.php");
require_once(MODULESPATH."/auth/constants/GroupConstants.php");
require_once(MODULESPATH."/auth/controllers/SessionManager.php");

class Secretary extends MX_Controller {

	public function index(){

		loadTemplateSafelyByGroup(GroupConstants::SECRETARY_GROUP,'secretary/secretary/secretary_home');
	}

	public function requestReport(){

		$session = getSession();
		$user = $session->getUserData();
		$userName = $user->getName();	
		
		$courses = $this->loadCourses();

		$courseData = array(
			'courses' => $courses,
			'userName' => $userName
		);

		loadTemplateSafelyByPermission(PermissionConstants::REQUEST_REPORT_PERMISSION, 'secretary/request/secretary_courses_request', $courseData);
	}

	public function enrollTeacher(){

		$session = getSession();
		$user = $session->getUserData();

		$courses = $this->loadCourses();

		$courseData = array(
			'courses' => $courses,
			'user' => $user
		);

		loadTemplateSafelyByPermission(PermissionConstants::ENROLL_TEACHER_PERMISSION, 'secretary/secretary/enroll_teacher', $courseData);
	}

	public function enrollStudent(){

		$courses = $this->loadCourses();

		$courseData = array(
			'courses' => $courses
		);

		loadTemplateSafelyByPermission(PermissionConstants::ENROLL_STUDENT_PERMISSION, 'secretary/secretary/secretary_enroll_student', $courseData);
	}

	public function enrollMasterMinds(){
		
		$courses = $this->loadCourses();

		$courseData = array(
			'courses' => $courses
		);

		loadTemplateSafelyByPermission(PermissionConstants::DEFINE_MASTERMIND_PERMISSION, 'secretary/secretary/secretary_enroll_master_mind', $courseData);
	}

	public function coursesStudents(){

		$courses = $this->loadCourses();

		$this->load->module("program/course");
		$courses = $this->course->getCoursesType($courses);
		
		$courseData = array(
			'courses' => $courses
		);

		loadTemplateSafelyByPermission(PermissionConstants::STUDENT_LIST_PERMISSION, 'secretary/secretary/secretary_courses_students', $courseData);
	}

	private function loadCourses(){

		$session = getSession();
		$loggedUserData = $session->getUserData();
		$currentUser = $loggedUserData->getId();

		$this->load->model("program/course_model");
		$allCourses = $this->course_model->getAllCourses();

		if($allCourses !== FALSE){

			$courses = array();
			$i = 0;
			foreach($allCourses as $course){

				$userHasSecretaryForThisCourse = $this->checkIfUserHasSecretaryOfThisCourse($course['id_course'], $currentUser);

				if($userHasSecretaryForThisCourse){
					$courses[$i] = $course;
					$i++;
				}
			}

			if(!sizeof($courses) > 0){
				$courses = FALSE;
			}

		}else{

			$courses = FALSE;
		}

		return $courses;
	}

	private function checkIfUserHasSecretaryOfThisCourse($courseId, $userId){

		$course = new Course();
		$foundSecretaries = $course->getCourseSecretaries($courseId);
		$userHasSecretary = FALSE;

		if ($foundSecretaries !== FALSE) {
			foreach ($foundSecretaries as $secretary) {
				if ($secretary['id_user'] === $userId) {
					$userHasSecretary = TRUE;
				}
			}
		}

		return $userHasSecretary;
	}


	public function courseTeachers($courseId){

		$this->load->model("program/course_model");
		$courseData = $this->course_model->getCourseById($courseId);

		$courseTeachers = $this->course_model->getCourseTeachers($courseId);

		$this->load->model("auth/module_model");
		$foundGroup = $this->module_model->getGroupByGroupName(GroupConstants::TEACHER_GROUP);

		if($foundGroup !== FALSE){
			$this->load->model("auth/usuarios_model");
			$teachers = $this->usuarios_model->getUsersOfGroup($foundGroup['id_group']);

			if($teachers !== FALSE){

				$allTeachers = array();

				foreach($teachers as $teacher){
					$allTeachers[$teacher['id']] = $teacher['name'];
				}
			}else{
				$allTeachers = FALSE;
			}

		}else{
			$allTeachers = FALSE;
		}

		$data = array(
			'course' => $courseData,
			'teachers' => $courseTeachers,
			'allTeachers' => $allTeachers
		);

		loadTemplateSafelyByPermission(PermissionConstants::ENROLL_TEACHER_PERMISSION, 'secretary/secretary/course_teachers', $data);
	}

	public function enrollTeacherToCourse(){

		$courseId = $this->input->post('courseId');
		$teacherId = $this->input->post('teacher');

		$this->load->model("program/course_model");
		$wasEnrolled = $this->course_model->enrollTeacherToCourse($teacherId, $courseId);

		if($wasEnrolled){

			$status = "success";
			$message = "Docente vinculado ao curso com sucesso.";
		}else{
			$status = "danger";
			$message = "Não foi possível vincular o docente ao curso.";
		}

		$session = getSession();
		$session->showFlashMessage($status, $message);
		redirect("secretary/courseTeachers/{$courseId}");
	}

	public function removeTeacherFromCourse($teacherId, $courseId){

		$this->load->model("program/course_model");
		$wasRemoved = $this->course_model->removeTeacherFromCourse($teacherId, $courseId);

		if($wasRemoved){
			$status = "success";
			$message = "Docente removido do curso com sucesso.";
		}else{
			$status = "danger";
			$message = "Não foi possível remover o docente ao curso.";
		}

		$session = getSession();
		$session->showFlashMessage($status, $message);
		redirect("secretary/courseTeachers/{$courseId}");
	}


	public function defineTeacherSituation(){

		$courseId = $this->input->post('courseId');
		$teacherId = $this->input->post('teacherId');
		$situation = $this->input->post('situation');

		$this->load->model("program/course_model");
		$wasDefined = $this->course_model->defineTeacherSituation($courseId, $teacherId, $situation);

		if($wasDefined){
			$status = "success";
			$message = "Situação do docente definida com sucesso.";
		}else{
			$status = "danger";
			$message = "Não foi possível definir a situação do docente.";
		}

		$session = getSession();
		$session->showFlashMessage($status, $message);
		redirect("secretary/courseTeachers/{$courseId}");
	}


	public function secretaryPrograms(){

		$programsAndCourses = $this->getSecretaryPrograms();		
		$programs = $programsAndCourses['programs'];
		$courses = $programsAndCourses['courses'];

		$data = array(
			'courses' => $courses,
			'programs' => $programs
		);

		loadTemplateSafelyByGroup(GroupConstants::ACADEMIC_SECRETARY_GROUP, 'secretary/secretary/secretary_programs', $data);
	}

	public function getSecretaryPrograms(){

		$session = getSession();
		$user = $session->getUserData();
		$secretaryId = $user->getId();

		$this->load->model("program/course_model");
		$courses = $this->course_model->getCoursesOfSecretary($secretaryId);

		$alreadyAddedPrograms = array();
		$programs = array();
		if($courses !== FALSE){

			foreach ($courses as $course) {

				$this->load->model("program/program_model");
				$courseId = $course['id_course'];
				$programId = $course['id_program'];

				if($programId != NULL){

					if(!isset($alreadyAddedPrograms[$programId])){
						$programs[] = $this->program_model->getProgramById($programId);
					}

					$alreadyAddedPrograms[$programId] = $programId;
				}
			}
			
		}else{
			$courses = array();
		}

		$result = array('programs' => $programs , 'courses' => $courses);
		
		return $result;
	}

}

