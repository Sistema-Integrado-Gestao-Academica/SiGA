<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(MODULESPATH."program/controllers/Course.php");
// require_once('Usuario.php');
// require_once('Module.php');
// require_once('Semester.php');
// require_once('Offer.php');
// require_once('Program.php');
require_once(MODULESPATH."/auth/constants/PermissionConstants.php");
require_once(MODULESPATH."/auth/constants/GroupConstants.php");
require_once(MODULESPATH."/auth/controllers/SessionManager.php");

class Secretary extends MX_Controller {

	public function index(){

		loadTemplateSafelyByGroup("secretario",'secretary/secretary_home');
	}

	public function guest_index(){

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

		loadTemplateSafelyByPermission(PermissionConstants::REQUEST_REPORT_PERMISSION, 'request/secretary_courses_request', $courseData);
	}

	public function enrollTeacher(){

		$courses = $this->loadCourses();

		$courseData = array(
			'courses' => $courses
		);

		loadTemplateSafelyByPermission(PermissionConstants::ENROLL_TEACHER_PERMISSION, 'secretary/enroll_teacher', $courseData);
	}

	public function enrollStudent(){

		$courses = $this->loadCourses();

		$courseData = array(
			'courses' => $courses
		);

		loadTemplateSafelyByPermission(PermissionConstants::ENROLL_STUDENT_PERMISSION, 'secretary/secretary_enroll_student', $courseData);
	}

	public function enrollMasterMinds(){
		
		$courses = $this->loadCourses();

		$courseData = array(
			'courses' => $courses
		);

		loadTemplateSafelyByPermission(PermissionConstants::DEFINE_MASTERMIND_PERMISSION, 'secretary/secretary_enroll_master_mind', $courseData);
	}

	public function coursesStudents(){

		$courses = $this->loadCourses();

		$this->load->module("program/course");
		$courses = $this->course->getCoursesType($courses);
		
		$courseData = array(
			'courses' => $courses
		);

		loadTemplateSafelyByPermission(PermissionConstants::STUDENT_LIST_PERMISSION, 'secretary/secretary_courses_students', $courseData);
	}

	private function loadCourses(){

		$session = getSession();
		$loggedUserData = $session->getUserData();
		$currentUser = $loggedUserData->getId();

		$this->load->model("course_model");
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


	public function courseSyllabus(){

		$this->load->model("program/semester_model");
		$currentSemester = $this->semester_model->getCurrentSemester();

		// Get the current user id
		$session = SessionManager::getInstance();
		$loggedUserData = $session->getUserData();
		$currentUser = $loggedUserData->getId();

		// Get the courses of the secretary
		$course = new Course();
		$courses = $course->getCoursesOfSecretary($currentUser);

		if($courses !== FALSE){

			$syllabus = new Syllabus();
			$coursesSyllabus = array();
			foreach ($courses as $course){

				$coursesSyllabus[$course['course_name']] = $syllabus->getCourseSyllabus($course['id_course']);
			}
		}else{
			$coursesSyllabus = FALSE;
		}

		$data = array(
			'current_semester' => $currentSemester,
			'courses' => $courses,
			'syllabus' => $coursesSyllabus
		);

		loadTemplateSafelyByPermission(PermissionConstants::COURSE_SYLLABUS_PERMISSION,'secretary/secretary_course_syllabus', $data);
	}

	public function courseTeachers($courseId){

		$course = new Course();
		$courseData = $course->getCourseById($courseId);

		$courseTeachers = $course->getCourseTeachers($courseId);

		$group = new Module();
		$foundGroup = $group->getGroupByName(GroupConstants::TEACHER_GROUP);

		if($foundGroup !== FALSE){
			$user = new Usuario();
			$teachers = $user->getUsersOfGroup($foundGroup['id_group']);

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

		loadTemplateSafelyByPermission(PermissionConstants::ENROLL_TEACHER_PERMISSION, 'secretary/course_teachers', $data);
	}

	public function enrollTeacherToCourse(){

		$courseId = $this->input->post('courseId');
		$teacherId = $this->input->post('teacher');

		$course = new Course();
		$wasEnrolled = $course->enrollTeacherToCourse($teacherId, $courseId);

		if($wasEnrolled){

			$status = "success";
			$message = "Docente vinculado ao curso com sucesso.";
		}else{
			$status = "danger";
			$message = "Não foi possível vincular o docente ao curso.";
		}

		$session = SessionManager::getInstance();
		$session->showFlashMessage($status, $message);
		redirect("secretary/courseTeachers/{$courseId}");
	}

	public function removeTeacherFromCourse($teacherId, $courseId){

		$course = new Course();
		$wasRemoved = $course->removeTeacherFromCourse($teacherId, $courseId);

		if($wasRemoved){
			$status = "success";
			$message = "Docente removido do curso com sucesso.";
		}else{
			$status = "danger";
			$message = "Não foi possível remover o docente ao curso.";
		}

		$session = SessionManager::getInstance();
		$session->showFlashMessage($status, $message);
		redirect("secretary/courseTeachers/{$courseId}");
	}


	public function defineTeacherSituation(){

		$courseId = $this->input->post('courseId');
		$teacherId = $this->input->post('teacherId');
		$situation = $this->input->post('situation');

		$course = new Course();
		$wasDefined = $course->defineTeacherSituation($courseId, $teacherId, $situation);

		if($wasDefined){
			$status = "success";
			$message = "Situação do docente definida com sucesso.";
		}else{
			$status = "danger";
			$message = "Não foi possível definir a situação do docente.";
		}

		$session = SessionManager::getInstance();
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

		loadTemplateSafelyByGroup(GroupConstants::ACADEMIC_SECRETARY_GROUP, 'secretary/secretary_programs', $data);
	}

	public function getSecretaryPrograms(){

		$session = SessionManager::getInstance();
		$user = $session->getUserData();
		$secretaryId = $user->getId();

		$courseController = new Course();
		$courses = $courseController->getCoursesOfSecretary($secretaryId);

		$alreadyAddedPrograms = array();
		$programs = array();
		if($courses !== FALSE){

			foreach ($courses as $course) {

				$program = new Program();
				$courseId = $course['id_course'];
				$programId = $course['id_program'];

				if($programId != NULL){

					if(!isset($alreadyAddedPrograms[$programId])){
						$programs[] = $program->getProgramById($programId);
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

