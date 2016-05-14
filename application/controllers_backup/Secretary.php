<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once('Course.php');
require_once('Usuario.php');
require_once('Module.php');
require_once('Semester.php');
require_once('Offer.php');
require_once('Program.php');
require_once(APPPATH."/constants/PermissionConstants.php");
require_once(APPPATH."/constants/GroupConstants.php");
require_once(APPPATH."/controllers/security/session/SessionManager.php");

class Secretary extends CI_Controller {

	public function index(){

		loadTemplateSafelyByGroup("secretario",'secretary/secretary_home');
	}

	public function guest_index(){

	}

	public function requestReport(){

		$courses = $this->loadCourses();

		$courseData = array(
			'courses' => $courses
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

		$courseData = array(
			'courses' => $courses
		);

		loadTemplateSafelyByPermission(PermissionConstants::STUDENT_LIST_PERMISSION, 'secretary/secretary_courses_students', $courseData);
	}

	private function loadCourses(){

		$session = SessionManager::getInstance();
		$loggedUserData = $session->getUserData();
		$currentUser = $loggedUserData->getId();

		$course = new Course();
		$allCourses = $course->listAllCourses();

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

	public function offerList(){

		$semester = new Semester();
		$currentSemester = $semester->getCurrentSemester();

		// Check if the logged user have admin permission
		$group = new Module();
		$isAdmin = $group->checkUserGroup(GroupConstants::ADMIN_GROUP);

		// Get the current user id
		$session = SessionManager::getInstance();
		$loggedUserData = $session->getUserData();
		$currentUser = $loggedUserData->getId();

		// Get the courses of the secretary
		$course = new Course();
		$courses = $course->getCoursesOfSecretary($currentUser);

		// Get the proposed offers of every course
		$offer = new Offer();
		if($courses !== FALSE){

			$proposedOffers = array();
			foreach($courses as $course){
				$courseId = $course['id_course'];
				$courseName = $course['course_name'];
				$proposedOffers[$courseName] = $offer->getCourseOfferList($courseId, $currentSemester['id_semester']);
			}

		}else{
			$proposedOffers = FALSE;
		}

		$data = array(
			'current_semester' => $currentSemester,
			'isAdmin' => $isAdmin,
			'proposedOffers' => $proposedOffers,
			'courses' => $courses
		);

		loadTemplateSafelyByPermission(PermissionConstants::OFFER_LIST_PERMISSION, 'secretary/secretary_offer_list', $data);
	}

	public function courseSyllabus(){

		$semester = new Semester();
		$currentSemester = $semester->getCurrentSemester();

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

	public function research_lines(){
		
		$this->load->model("course_model");

		$session = getSession();
		$loggedUserData = $session->getUserData();
		$userId = $loggedUserData->getId();

		$secretaryCourses = $this->course_model->getCoursesOfSecretary($userId);

		$this->loadResearchLinesPage($secretaryCourses);
	}

	public function loadResearchLinesPage($secretaryCourses){
		$this->load->model("course_model");

		if($secretaryCourses !== FALSE){

			foreach ($secretaryCourses as $key => $course){

				$researchLines[$key] = $this->course_model->getCourseResearchLines($course['id_course']);
				$courses[$key] = $course;
			}
		}else{
			$researchLines = FALSE;
			$courses = FALSE;
		}

		$data = array(
			'research_lines' => $researchLines,
			'courses' => $courses
		);

		loadTemplateSafelyByPermission('research_lines', 'secretary/secretary_research_lines', $data);
	}

	public function saveResearchLine(){
		$this->load->library("form_validation");
		$this->form_validation->set_rules("researchLine", "Linha de Pesquisa", "required|trim|xss_clean|callback__alpha_dash_space");
		$this->form_validation->set_error_delimiters("<p class='alert-danger'>", "</p>");
		$success = $this->form_validation->run();

		$this->load->model("course_model");
		$session = SessionManager::getInstance();
		if ($success) {
			$researchLine  = $this->input->post("researchLine");
			$researchCourse   = $this->input->post("research_course");

			$newResearchLine = array(
					'description'    => $researchLine,
					'id_course' => $researchCourse
			);


			$wasSaved = $this->course_model->saveResearchLine($newResearchLine);
			if ($wasSaved){
				$status = "success";
				$message = "Linha de pesquisa salva do curso ".$course." com sucesso.";
			}else{
				$status = "danger";
				$message = "Não foi possível salvar o linha de pesquisa do curso ". $course;
			}

			$session->showFlashMessage($status,$message);
			redirect("research_lines/");
		} else {
			$status = "danger";
			$message = "Não foi possível salvar o linha de pesquisa. Tente Novamente";

			$session->showFlashMessage($status,$message);
			redirect("research_lines/");

		}
	}

	public function updateResearchLine(){
		$this->load->library("form_validation");
		$this->form_validation->set_rules("researchLine", "Linha de Pesquisa", "required|trim|xss_clean|callback__alpha_dash_space");
		$this->form_validation->set_error_delimiters("<p class='alert-danger'>", "</p>");
		$success = $this->form_validation->run();

		$this->load->model("course_model");
		$session = SessionManager::getInstance();
		
		if ($success) {
			$researchLine  = $this->input->post("researchLine");
			$researchCourse   = $this->input->post("research_course");
			$researchLineId = $this->input->post("id_research_line");

			$updateResearchLine = array(
					'description'    => $researchLine,
					'id_course' => $researchCourse
			);


			$wasSaved = $this->course_model->updateResearchLine($updateResearchLine, $researchLineId);
			if ($wasSaved){
				$status = "success";
				$message = "Linha de pesquisa alterada com sucesso.";
			}else{
				$status = "danger";
				$message = "Não foi possível alterar o linha de pesquisa.";
			}

			$session->showFlashMessage($status,$message);
			redirect("research_lines/");
		} else {
			$status = "danger";
			$message = "Não foi possível alterar o linha de pesquisa. Tente Novamente";

			$session->showFlashMessage($status,$message);
			redirect("research_lines/");

		}
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

