<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once('login.php');
require_once('course.php');
require_once('module.php');
require_once('semester.php');
require_once('offer.php');
require_once('syllabus.php');
require_once('request.php');

require_once(APPPATH."/constants/GroupConstants.php");
require_once(APPPATH."/constants/PermissionConstants.php");

require_once(APPPATH."/controllers/security/session/SessionManager.php");

require_once(APPPATH."/data_types/User.php");
require_once(APPPATH."/exception/UserException.php");
require_once(APPPATH."/exception/LoginException.php");

class Usuario extends CI_Controller {

	public function loadModel(){
		$this->load->model("usuarios_model");
	}

	public function validateUser($login, $password){

		$this->load->model("usuarios_model");

		try{
			$foundUser = $this->usuarios_model->validateUser($login, $password);
		}catch(LoginException $e){
			throw $e;
		}

		if($foundUser !== FALSE){
			try{
				$id = $foundUser['id'];
				$name = $foundUser['name'];
				$login = $foundUser['login'];
				$email = $foundUser['email'];

				$user = new User($id, $name, FALSE, $email, $login);

			}catch(UserException $e){
				$user = FALSE;
			}
		}else{
			$user = FALSE;
		}

		return $user;
	}

	public function usersReport(){

		$allUsers = $this->getAllUsers();

		$group = new Module();
		$allGroups = $group->getExistingModules();

		$data = array(
			'allUsers' => $allUsers,
			'allGroups' => $allGroups
		);

		loadTemplateSafelyByPermission('user_report','usuario/user_report', $data);
	}

	public function manageGroups($idUser){

		$group = new Module();
		$userGroups = $group->getUserGroups($idUser);
		$allGroups = $group->getExistingModules();

		$data = array(
			'idUser' => $idUser,
			'userGroups' => $userGroups,
			'allGroups' => $allGroups
		);

		loadTemplateSafelyByPermission('user_report','usuario/manage_user_groups', $data);
	}

	public function listUsersOfGroup($idGroup){

		$this->load->model("usuarios_model");

		$usersOfGroup = $this->usuarios_model->getUsersOfGroup($idGroup);

		$data = array(
			'idGroup' => $idGroup,
			'usersOfGroup' => $usersOfGroup
		);

		loadTemplateSafelyByPermission('user_report', 'usuario/users_of_group', $data);
	}

	public function createCourseResearchLine(){
		$this->load->model("course_model");

		$session = SessionManager::getInstance();
		$loggedUserData = $session->getUserData();
		$userId = $loggedUserData->getId();

		$secretaryCourses = $this->course_model->getCoursesOfSecretary($userId);

		if($secretaryCourses !== FALSE){

			foreach ($secretaryCourses as $key => $courses){
				$course[$courses['id_course']] = $courses['course_name'];
			}
		}else{
			$course = FALSE;
		}

		$data = array(
			'courses'=> $course
		);

		loadTemplateSafelyByPermission('research_lines', 'secretary/create_research_line', $data);
	}


	public function updateCourseResearchLine($researchId, $courseId){
		$this->load->model("course_model");

		$actualCourse = $this->course_model->getCourseById($courseId);
		$actualCourseForm = $actualCourse['id_course'];

		$description = $this->course_model->getResearchDescription($researchId,$courseId);

		$session = SessionManager::getInstance();
		$loggedUserData = $session->getUserData();
		$userId = $loggedUserData->getId();

		$secretaryCourses = $this->course_model->getCoursesOfSecretary($userId);

		foreach ($secretaryCourses as $key => $courses){
			$course[$courses['id_course']] = $courses['course_name'];
		}

		$data = array(
			'researchId' => $researchId,
			'description' => $description,
			'actualCourse' => $actualCourseForm,
			'courses' => $course
		);

		loadTemplateSafelyByPermission('research_lines', 'secretary/update_research_line', $data);
	}

	public function removeCourseResearchLine($researchLineId,$course){

		$this->load->model("course_model");

		$wasRemoved = $this->course_model->removeCourseResearchLine($researchLineId);

		if($wasRemoved){
			$status = "success";
			$message = "Linha de pesquisa removida do curso ".$course." com sucesso.";
		}else{
			$status = "danger";
			$message = "Não foi possível remover o linha de pesquisa do curso ". $course;
		}

		$session = SessionManager::getInstance();
		$session->showFlashMessage($status, $message);
		redirect("research_lines/");

	}

	public function secretary_research_lines(){
		$this->load->model("course_model");

		$session = SessionManager::getInstance();
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

		loadTemplateSafelyByPermission('research_lines', 'usuario/secretary_research_lines', $data);
	}

	public function removeAllUsersOfGroup($idGroup){

		$this->load->model("usuarios_model");

		$wasDeleted = $this->usuarios_model->removeAllUsersOfGroup($idGroup);

		if($wasDeleted){
			$status = "success";
			$message = "Usuários removidos com sucesso.";
		}else{
			$status = "danger";
			$message = "Não foi possível remover os usuários do grupo informado. Tente novamente.";
		}

		$session = SessionManager::getInstance();
		$session->showFlashMessage($status, $message);
		redirect("user_report");
	}

	public function addGroupToUser($idUser, $idGroup){

		$this->load->model('usuarios_model');
		$wasSaved = $this->usuarios_model->addGroupToUser($idUser, $idGroup);

		if($wasSaved){
			$status = "success";
			$message = "Grupo adicionado com sucesso.";
		}else{
			$status = "danger";
			$message = "Não foi possível adicionar o grupo informado. Tente novamente.";
		}

		$session = SessionManager::getInstance();
		$session->showFlashMessage($status, $message);
		redirect("usuario/manageGroups/{$idUser}");
	}

	public function removeUserGroup($idUser, $idGroup){

		$this->load->model('usuarios_model');
		$wasDeleted = $this->usuarios_model->removeUserGroup($idUser, $idGroup);

		if($wasDeleted){
			$status = "success";
			$message = "Grupo removido com sucesso.";
		}else{
			$status = "danger";
			$message = "Não foi possível remover o grupo informado. Tente novamente.";
		}

		$session = SessionManager::getInstance();
		$session->showFlashMessage($status, $message);
		redirect("usuario/manageGroups/{$idUser}");
	}

	public function removeUserFromGroup($idUser, $idGroup){

		$this->load->model('usuarios_model');
		$wasDeleted = $this->usuarios_model->removeUserGroup($idUser, $idGroup);

		if($wasDeleted){
			$status = "success";
			$message = "Usuario removido com sucesso.";
		}else{
			$status = "danger";
			$message = "Não foi possível remover o usuário informado. Tente novamente.";
		}

		$session = SessionManager::getInstance();
		$session->showFlashMessage($status, $message);
		redirect("usuario/listUsersOfGroup/{$idGroup}");
	}

	public function checkIfUserExists($idUser){

		$this->load->model('usuarios_model');

		$userExists = $this->usuarios_model->checkIfUserExists($idUser);

		return $userExists;
	}

	public function getAllUsers(){

		$this->load->model('usuarios_model');

		$allUsers = $this->usuarios_model->getAllUsers();

		return $allUsers;
	}

	public function getUsersToBeSecretaries(){

		$this->load->model('usuarios_model');

		$group = new Module();
		$groupData = $group->getGroupByName(GroupConstants::SECRETARY_GROUP);
		$idGroup = $groupData['id_group'];

		$users = $this->usuarios_model->getUsersOfGroup($idGroup);

		return $users;
	}

	public function getUserCourses($userId){

		$this->load->model('usuarios_model');

		$userCourses = $this->usuarios_model->getUserCourse($userId);

		return $userCourses;
	}

	public function student_index(){

		$this->load->model('usuarios_model');

		$session = SessionManager::getInstance();
		$loggedUserData = $session->getUserData();
		$userId = $loggedUserData->getId();

		$userStatus = $this->usuarios_model->getUserStatus($userId);
		$userCourse = $this->usuarios_model->getUserCourse($userId);

		$semester = new Semester();
		$currentSemester = $semester->getCurrentSemester();

		$userData = array(
			'userData' => $loggedUserData,
			'status' => $userStatus,
			'courses' => $userCourse,
			'currentSemester' => $currentSemester
		);

		// On auth_helper
		loadTemplateSafelyByGroup(GroupConstants::STUDENT_GROUP, 'usuario/student_home', $userData);
	}

	public function getUserStatus($userId){

		$this->load->model('usuarios_model');

		$userStatus = $this->usuarios_model->getUserStatus($userId);

		return $userStatus;
	}

	public function studentInformationsForm(){

		$this->load->model('usuarios_model');

		$session = SessionManager::getInstance();
		$loggedUserData = $session->getUserData();
		$userId = $loggedUserData->getId();

		$userStatus = $this->usuarios_model->getUserStatus($userId);
		$userCourse = $this->usuarios_model->getUserCourse($userId);

		$semester = new Semester();
		$currentSemester = $semester->getCurrentSemester();

		$userData = array(
				'userData' => $loggedUserData,
				'status' => $userStatus,
				'courses' => $userCourse,
				'currentSemester' => $currentSemester
		);

		// On auth_helper
		loadTemplateSafelyByGroup(GroupConstants::STUDENT_GROUP, 'usuario/student_specific_data_form', $userData);
	}

	public function studentCoursePage($courseId, $userId){

		$userData = $this->getUserById($userId);

		$course = new Course();
		$courseData = $course->getCourseById($courseId);

		$data = array(
			'course' => $courseData,
			'user' => $userData
		);

		loadTemplateSafelyByGroup(GroupConstants::STUDENT_GROUP, 'usuario/student_course_page', $data);
	}

	public function student_offerList($courseId){

		$semester = new Semester();
		$currentSemester = $semester->getCurrentSemester();

		$course = new Course();
		$courseData = $course->getCourseById($courseId);

		$offer = new Offer();
		$offerListDisciplines = $offer->getCourseApprovedOfferListDisciplines($courseId, $currentSemester['id_semester']);

		$session = SessionManager::getInstance();
		$loggedUserData = $session->getUserData();
		$userId = $loggedUserData->getId();

		$data = array(
			'currentSemester' => $currentSemester,
			'course' => $courseData,
			'offerListDisciplines' => $offerListDisciplines,
			'userId' => $userId
		);

		loadTemplateSafelyByGroup(GroupConstants::STUDENT_GROUP, 'usuario/student_offer_list', $data);
	}

	public function guest_index(){

	}

	public function secretary_index(){

		loadTemplateSafelyByGroup("secretario",'usuario/secretary_home');
	}

	public function secretary_coursesStudents(){

		$courses = $this->loadCourses();

		$courseData = array(
			'courses' => $courses
		);

		loadTemplateSafelyByPermission(PermissionConstants::STUDENT_LIST_PERMISSION, 'usuario/secretary_courses_students', $courseData);
	}

	public function secretary_enrollStudent(){

		$courses = $this->loadCourses();

		$courseData = array(
			'courses' => $courses
		);

		loadTemplateSafelyByPermission(PermissionConstants::ENROLL_STUDENT_PERMISSION, 'usuario/secretary_enroll_student', $courseData);
	}

	public function secretary_enrollMasterMinds(){
		$courses = $this->loadCourses();

		$courseData = array(
				'courses' => $courses
		);

		loadTemplateSafelyByPermission(PermissionConstants::DEFINE_MASTERMIND_PERMISSION, 'usuario/secretary_enroll_master_mind', $courseData);
	}

	public function secretary_enrollTeacher(){

		$courses = $this->loadCourses();

		$courseData = array(
			'courses' => $courses
		);

		loadTemplateSafelyByPermission(PermissionConstants::ENROLL_TEACHER_PERMISSION, 'secretary/enroll_teacher', $courseData);
	}

	public function secretary_requestReport(){

		$courses = $this->loadCourses();

		$courseData = array(
			'courses' => $courses
		);

		loadTemplateSafelyByPermission(PermissionConstants::REQUEST_REPORT_PERMISSION, 'request/secretary_courses_request', $courseData);
	}

	public function secretary_offerList(){

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

		loadTemplateSafelyByPermission(PermissionConstants::OFFER_LIST_PERMISSION, 'usuario/secretary_offer_list', $data);
	}

	public function secretary_courseSyllabus(){

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

		loadTemplateSafelyByPermission(PermissionConstants::COURSE_SYLLABUS_PERMISSION,'usuario/secretary_course_syllabus', $data);
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
		$foundSecretaries = $course->getCourseSecrecretary($courseId);
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

	public function formulario() {
		$this->load->model('usuarios_model');
		$users = $this->usuarios_model->buscaTodos();

		$session = SessionManager::getInstance();

		if ($users && !$session->isLogged()) {

			$session->showFlashMessage("danger", "Você deve ter permissão do administrador. Faça o login.");
			redirect('login');
		} else {

			$userGroups = $this->getAllowedUserGroupsForFirstRegistration();

			$data = array('user_groups' => $userGroups);
			$this->load->template("usuario/formulario", $data);
		}
	}

	public function formulario_entrada() {

		$this->load->template("usuario/formulario_entrada");

	}

	public function conta(){

		$session = SessionManager::getInstance();
		$loggedUser = $session->getUserData();

		$data = array("user" => $loggedUser);

		$this->load->template("usuario/conta", $data);
	}

	public function novo() {
		$this->load->library("form_validation");
		$this->form_validation->set_rules("nome", "Nome", "required|trim|xss_clean|callback__alpha_dash_space");
		$this->form_validation->set_rules("cpf", "CPF", "required|valid_cpf");
		$this->form_validation->set_rules("email", "E-mail", "required|valid_email");
		$this->form_validation->set_rules("login", "Login", "required|alpha_dash");
		$this->form_validation->set_rules("senha", "Senha", "required");
		$this->form_validation->set_error_delimiters("<p class='alert-danger'>", "</p>");
		$success = $this->form_validation->run();

		if ($success) {
			$nome  = $this->input->post("nome");
			$cpf   = $this->input->post("cpf");
			$email = $this->input->post("email");
			$group = $this->input->post("userGroup");
			$login = $this->input->post("login");
			$senha = md5($this->input->post("senha"));

			$usuario = array(
				'name'     => $nome,
				'cpf'      => $cpf,
				'email'    => $email,
				'login'    => $login,
				'password' => $senha
			);
			
			$this->load->model("usuarios_model");
			
			$session = SessionManager::getInstance();
			
			$usuarioExiste = $this->usuarios_model->buscaPorLoginESenha($login);

			if ($usuarioExiste) {
				$status  = "danger";
				$message =  "Usuário já existe no sistema.";
				redirect("usuario/formulario");
			} else {
				$this->usuarios_model->salva($usuario);
				$this->usuarios_model->saveGroup($usuario, $group);
				$session->showFlashMessage("success", "Usuário \"{$usuario['login']}\" cadastrado com sucesso");
				redirect("/");
			}
			$session->showFlashMessage($status, $message);

		} else {
			$userGroups = $this->getAllowedUserGroupsForFirstRegistration();

			$data = array('user_groups' => $userGroups);
			$this->load->template("usuario/formulario", $data);
		}
	}

	public function altera() {

		$this->load->library("form_validation");
		$this->load->model('usuarios_model');

		$session = SessionManager::getInstance();
		$usuarioLogado = $session->getUserData();

		$this->form_validation->set_rules("nome", "Nome", "trim|xss_clean|callback__alpha_dash_space");
		$this->form_validation->set_rules("email", "E-mail", "valid_email");
		$this->form_validation->set_error_delimiters("<p class='alert-danger'>", "</p>");
		$success = $this->form_validation->run();

		if ($success) {

			$user = $this->getAccountForm($usuarioLogado);

			$alterado = $this->usuarios_model->altera($user);

			if ($alterado) {

				$session->unsetUserData();
				$session->login($user);

				$session->showFlashMessage("success", "Os dados foram alterados");

			}else{
				$session->showFlashMessage("danger", "Os dados não foram alterados");
			}

			redirect('usuario/conta');
		} else {
			$this->load->template("usuario/conta");
		}
	}

	public function remove() {

		$this->load->model("usuarios_model");

		$session = SessionManager::getInstance();
		$loggedUser = $session->getUserData();

		$userWasDeleted = $this->usuarios_model->remove(array('login' => $loggedUser->getLogin()));
		if($userWasDeleted){

			$login = new Login();
			$login->logout("Usuário \"{$loggedUser->getName()}\" removido", "success", "login");

		}else{
			$data = array('user' => $loggedUser);
			$this->load->template("usuario/conta", $data);
		}

	}

	public function searchForStudent(){

		$studentNameToSearch = $this->input->post('student_name');

		$students = $this->getRegisteredStudentsByName($studentNameToSearch);

		$studentIds = array();
		$studentNames = array();
		$i = 0;
		foreach($students as $student){
			$studentIds[$i] = $student['id'];
			$studentNames[$i] = $student['name'];
			$i++;
		}

		$studentsToDropdown = array_combine($studentIds, $studentNames);

		// On tables helper
		displayRegisteredStudents($studentsToDropdown, $studentNameToSearch);
	}

	public function saveStudentBasicInformation(){
		$this->load->library("form_validation");
		$this->form_validation->set_rules("email", "E-mail", "required|valid_email");
		$this->form_validation->set_rules("home_phone_number", "Telefone Residencial", "required|alpha_dash");
		$this->form_validation->set_rules("cell_phone_number", "Telefone Celular", "required|alpha_dash");
		$this->form_validation->set_error_delimiters("<p class='alert-danger'>", "</p>");
		$success = $this->form_validation->run();

		if ($success){
			$email = $this->input->post("email");
			$cellPhone = $this->input->post("cell_phone_number");
			$homePhone = $this->input->post("home_phone_number");
			$studentRegistration = $this->input->post("student_registration");
			$idUser = $this->input->post("id_user");

			$studentBasics = array(
					'email'    => $email,
					'cell_phone_number'    => $cellPhone,
					'home_phone_number' => $homePhone,
					'student_registration' => $studentRegistration,
					'id_user' => $idUser
			);

			$this->load->model("usuarios_model");

			$savedBasicInformation = $this->usuarios_model->saveStudentBasicInformation($studentBasics);

			if($savedBasicInformation){
				$updateStatus = "success";
				$updateMessage = "Novos dados cadastrados com sucesso";
			}else{
				$updateStatus = "danger";
				$updateMessage = "Não foi possível salvar seus novos dados. Tente novamente.";
			}

		} else {
			$updateStatus = "danger";
			$updateMessage = "Não foi possível salvar seus novos dados. Tente novamente.";
		}
			$session = SessionManager::getInstance();
			$session->showFlashMessage($updateStatus, $updateMessage);
			redirect("student_information/");
	}

	public function updateStudentBasicInformation(){
		$this->load->library("form_validation");
		$this->form_validation->set_rules("email", "E-mail", "required|valid_email");
		$this->form_validation->set_rules("home_phone_number", "Telefone Residencial", "required|alpha_dash");
		$this->form_validation->set_rules("cell_phone_number", "Telefone Celular", "required|alpha_dash");
		$this->form_validation->set_error_delimiters("<p class='alert-danger'>", "</p>");
		$success = $this->form_validation->run();

		if ($success){
			$email = $this->input->post("email");
			$cellPhone = $this->input->post("cell_phone_number");
			$homePhone = $this->input->post("home_phone_number");
			$studentRegistration = $this->input->post("student_registration");
			$idUser = $this->input->post("id_user");

			$studentBasicsUpdate = array(
					'email'    => $email,
					'cell_phone_number'    => $cellPhone,
					'home_phone_number' => $homePhone
			);

			$whereUpdate = array(
					'student_registration' => $studentRegistration,
					'id_user' => $idUser
			);

			$this->load->model("usuarios_model");

			$updatedBasicInformation = $this->usuarios_model->updateStudentBasicInformation($studentBasicsUpdate, $whereUpdate);

			if($updatedBasicInformation){
				$updateStatus = "success";
				$updateMessage = "Novos dados alterados com sucesso";
			}else{
				$updateStatus = "danger";
				$updateMessage = "Não foi possível alterar seus novos dados. Tente novamente.";
			}

		} else {
			$updateStatus = "danger";
			$updateMessage = "Não foi possível alterar seus novos dados. Tente novamente.";
		}
		$session = SessionManager::getInstance();
		$session->showFlashMessage($updateStatus, $updateMessage);
		redirect("student_information/");
	}

	private function getRegisteredStudentsByName($userName){

		define("GUEST", "convidado");

		$foundUsers = $this->getUserByName($userName);

		$students = array();

		$usersWasFound = $foundUsers !== FALSE;
		if($usersWasFound){

			$group = new Module();

			$i = 0;
			foreach($foundUsers as $user){
				$userId = $user['id'];
				$userGroups = $group->checkModules($userId);

				// $userIsStudent = $this->checkIfIsStudent($userGroups);
				$userIsGuest = $this->checkIfIsGuest($userGroups);

				if($userIsGuest){
					$students[$i] = $user;
					$i++;
				}
			}
		}

		return $students;
	}

	private function checkIfIsStudent($userGroups){

		define("STUDENT", GroupConstants::STUDENT_GROUP);

		$isStudent = FALSE;
		foreach($userGroups as $group_name){
			if($group_name == STUDENT){
				$isStudent = TRUE;
				break;
			}
		}

		return $isStudent;
	}

	private function checkIfIsGuest($userGroups){

		$isGuest = FALSE;
		foreach($userGroups as $group_name){
			if($group_name == GUEST){
				$isGuest = TRUE;
				break;
			}
		}

		return $isGuest;
	}

	public function getUserByName($userName){

		$this->load->model('usuarios_model');

		$foundUser = $this->usuarios_model->getUserByName($userName);

		return $foundUser;
	}

	public function getUsersOfGroup($idGroup){

		$this->load->model('usuarios_model');

		$groups = $this->usuarios_model->getUsersOfGroup($idGroup);

		return $groups;
	}

	public function getUserById($userId){

		$this->load->model('usuarios_model');

		$foundUser = $this->usuarios_model->getUserById($userId);

		return $foundUser;
	}

	public function getUserGroupNameByIdGroup($groupId){
		$this->load->model('usuarios_model');

		$groupName = $this->usuarios_model->getUserGroupNameByIdGroup($groupId);

		return $groupName;
	}

	/**
	 * Get all the user types from database into an array.
	 * @return An array with all user types on database as id => type_name
	 */
	public function getUserGroups(){

		$this->load->model("usuarios_model");
		$user_groups = $this->usuarios_model->getAllUserGroups();

		$user_groups_to_array = $this->turnUserGroupsToArray($user_groups);

		return $user_groups_to_array;
	}

	public function getAllowedUserGroupsForFirstRegistration(){

		$this->load->model("usuarios_model");
		$userGroups = $this->usuarios_model->getAllowedUserGroupsForFirstRegistration();

		$userGroupsArray = $this->turnUserGroupsToArray($userGroups);

		return $userGroupsArray;
	}

	public function getUserNameById($idUser){
		$this->load->model('usuarios_model');
		$userName = $this->usuarios_model->getNameByUserId($idUser);

		return $userName;
	}

		public function getStudentBasicInformation($idUser){
		$this->load->model('usuarios_model');
		$userData = $this->usuarios_model->getStudentBasicInformation($idUser);

		return $userData;
	}

	/**
	  * Join the id's and names of user types into an array as key => value.
	  * Used to the user type form
	  * @param $user_groups - The array that contains the tuples of user_groups
	  * @return An array with the id's and user types names as id => user_group_name
	  */
	private function turnUserGroupsToArray($user_groups){
		// Quantity of user types registered
		$quantity_of_user_groups = sizeof($user_groups);

		for($cont = 0; $cont < $quantity_of_user_groups; $cont++){
			$keys[$cont] = $user_groups[$cont]['id_group'];
			$values[$cont] = $user_groups[$cont]['group_name'];
		}

		$form_user_groups = array_combine($keys, $values);

		return $form_user_groups;
	}

	private function getAccountForm($loggedUser) {

		$name = $this->input->post("nome");
		$email = $this->input->post("email");
		$login = $loggedUser->getLogin();
		$password = $this->input->post("senha");
		$new_password = md5($this->input->post("nova_senha"));

		$blank_password = md5("");

		$this->load->model('usuarios_model');

		$session = SessionManager::getInstance();
		try{
			$foundUser = $this->validateUser($login, $password);

			if($foundUser !== FALSE){

				if ($new_password !== $blank_password) {

					if (empty($name)) {
						$name = $user->getName();
					}

					if (empty($email)) {
						$email = $user->getEmail();
					}

					try{
						$user = new User($foundUser->getId(), $name, FALSE, $email, $foundUser->getLogin(), $new_password);

						return $user;

					}catch(UserException $e){
						$session->showFlashMessage("danger", $e->getMessage());
						redirect("usuario/conta");
					}

				}else{
					$session->showFlashMessage("danger", "A nova senha não pode estar em branco.");
					redirect("usuario/conta");
				}
			}else{
				$session->showFlashMessage("danger", "Senha atual incorreta.");
				redirect("usuario/conta");
			}
		}catch(LoginException $e){
			$session->showFlashMessage("danger", "Senha atual incorreta.");
			redirect("usuario/conta");
		}
	}

	/**
	 * Join the id's and names of users into an array as key => value.
	 * Used to the update course form
	 * @param $useres - The array that contains the tuples of users
	 * @return An array with the id's and users names as id => name
	 */
	private function turnUsersToArray($users){
		// Quantity of course types registered
		$quantity_of_course_types = sizeof($users);

		for($cont = 0; $cont < $quantity_of_course_types; $cont++){
			$keys[$cont] = $users[$cont]['id'];
			$values[$cont] = ucfirst($users[$cont]['name']);
		}

		$form_users = array_combine($keys, $values);

		return $form_users;
	}

}

function alpha_dash_space($str) {
	return ( ! preg_match("/^([-a-z_ ])+$/i", $str)) ? FALSE : TRUE;
}