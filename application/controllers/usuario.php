<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once('course.php');
require_once('module.php');
require_once('semester.php');
require_once('offer.php');
require_once('syllabus.php');
require_once('request.php');
require_once(APPPATH."/constants/GroupConstants.php");
require_once(APPPATH."/constants/PermissionConstants.php");
require_once(APPPATH."/data_types/notification/emails/RestorePasswordEmail.php");
require_once(APPPATH."/data_types/User.php");

class Usuario extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('usuarios_model');
	}

	public function loadModel(){
		$this->load->model("usuarios_model");
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

		$loggedUserData = $this->session->userdata("current_user");
		$userId = $loggedUserData['user']['id'];

		$secretaryCourses = $this->course_model->getCoursesOfSecretary($userId);

		foreach ($secretaryCourses as $key => $courses){
			$course[$courses['id_course']] = $courses['course_name'];
		}

		$data = array('courses'=> $course);

		loadTemplateSafelyByPermission('research_lines', 'secretary/create_research_line', $data);
	}


	public function updateCourseResearchLine($researchId, $courseId){
		$this->load->model("course_model");

		$actualCourse = $this->course_model->getCourseById($courseId);
		$actualCourseForm = $actualCourse['id_course'];

		$description = $this->course_model->getResearchDescription($researchId,$courseId);

		$loggedUserData = $this->session->userdata("current_user");
		$userId = $loggedUserData['user']['id'];

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

	public function secretary_research_lines(){
		$this->load->model("course_model");

		$loggedUserData = $this->session->userdata("current_user");
		$userId = $loggedUserData['user']['id'];

		$secretaryCourses = $this->course_model->getCoursesOfSecretary($userId);

		$this->loadResearchLinesPage($secretaryCourses);
	}

	public function loadResearchLinesPage($secretaryCourses){
		$this->load->model("course_model");

		foreach ($secretaryCourses as $key => $course){

			$researchLines[$key] = $this->course_model->getCourseResearchLines($course['id_course']);
			$courses[$key] = $course;
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

		$this->session->set_flashdata($status, $message);
		redirect("user_report");
	}

	public function addGroupToUser($idUser, $idGroup){

		$wasSaved = $this->usuarios_model->addGroupToUser($idUser, $idGroup);

		if($wasSaved){
			$status = "success";
			$message = "Grupo adicionado com sucesso.";
		}else{
			$status = "danger";
			$message = "Não foi possível adicionar o grupo informado. Tente novamente.";
		}

		$this->session->set_flashdata($status, $message);
		redirect("usuario/manageGroups/{$idUser}");
	}

	/**
	 * Get the group of the user to edit program
	 * @param userId - The id of the current user
	 * @return userGroup - Return the academic secretary or admin group
	 */
	public function getGroup(){

		$userGroup = "";

		$session = $this->session->userdata("current_user");
		$userId = $session['user']['id'];

		$userGroups = $this->usuarios_model->getGroups($userId);

		if($userGroups !== FALSE){

			foreach ($userGroups as $userGroup) {
				if($userGroup['group_name'] == GroupConstants::ACADEMIC_SECRETARY_GROUP){
					$userGroup = GroupConstants::ACADEMIC_SECRETARY_GROUP;
					break;
				}
				elseif ($userGroup['group_name'] == GroupConstants::ADMIN_GROUP) {
					$userGroup = GroupConstants::ADMIN_GROUP;
					break;
				}
			}
		}


		return $userGroup;
	}

	public function removeUserGroup($idUser, $idGroup){

		$wasDeleted = $this->usuarios_model->removeUserGroup($idUser, $idGroup);

		if($wasDeleted){
			$status = "success";
			$message = "Grupo removido com sucesso.";
		}else{
			$status = "danger";
			$message = "Não foi possível remover o grupo informado. Tente novamente.";
		}

		$this->session->set_flashdata($status, $message);
		redirect("usuario/manageGroups/{$idUser}");
	}

	public function removeUserFromGroup($idUser, $idGroup){

		$wasDeleted = $this->usuarios_model->removeUserGroup($idUser, $idGroup);

		if($wasDeleted){
			$status = "success";
			$message = "Usuario removido com sucesso.";
		}else{
			$status = "danger";
			$message = "Não foi possível remover o usuário informado. Tente novamente.";
		}

		$this->session->set_flashdata($status, $message);
		redirect("usuario/listUsersOfGroup/{$idGroup}");
	}

	public function checkIfUserExists($idUser){

		$userExists = $this->usuarios_model->checkIfUserExists($idUser);

		return $userExists;
	}

	public function getAllUsers(){

		$allUsers = $this->usuarios_model->getAllUsers();

		return $allUsers;
	}

	public function getUsersToBeSecretaries(){

		$group = new Module();
		$groupData = $group->getGroupByName(GroupConstants::SECRETARY_GROUP);
		$idGroup = $groupData['id_group'];

		$users = $this->usuarios_model->getUsersOfGroup($idGroup);

		return $users;
	}

	public function getUserCourses($userId){

		$userCourses = $this->usuarios_model->getUserCourse($userId);

		return $userCourses;
	}

	public function student_index(){

		$loggedUserData = $this->session->userdata("current_user");
		$userId = $loggedUserData['user']['id'];

		$userStatus = $this->usuarios_model->getUserStatus($userId);
		$userCourse = $this->usuarios_model->getUserCourse($userId);

		$semester = new Semester();
		$currentSemester = $semester->getCurrentSemester();

		$userData = array(
			'userData' => $loggedUserData['user'],
			'status' => $userStatus,
			'courses' => $userCourse,
			'currentSemester' => $currentSemester
		);

		// On auth_helper
		loadTemplateSafelyByGroup("estudante", 'usuario/student_home', $userData);
	}

	public function getUserStatus($userId){

		$this->load->model('usuarios_model');

		$userStatus = $this->usuarios_model->getUserStatus($userId);

		return $userStatus;
	}

	public function studentCoursePage($courseId, $userId){

		$userData = $this->getUserById($userId);

		$course = new Course();
		$courseData = $course->getCourseById($courseId);

		$data = array(
			'course' => $courseData,
			'user' => $userData
		);

		loadTemplateSafelyByGroup("estudante", 'usuario/student_course_page', $data);
	}

	public function student_offerList($courseId){

		$semester = new Semester();
		$currentSemester = $semester->getCurrentSemester();

		$course = new Course();
		$courseData = $course->getCourseById($courseId);

		$offer = new Offer();
		$offerListDisciplines = $offer->getCourseApprovedOfferListDisciplines($courseId, $currentSemester['id_semester']);

		$data = array(
			'currentSemester' => $currentSemester,
			'course' => $courseData,
			'offerListDisciplines' => $offerListDisciplines
		);

		loadTemplateSafelyByGroup("estudante", 'usuario/student_offer_list', $data);
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
		$logged_user_data = $this->session->userdata("current_user");
		$currentUser = $logged_user_data['user']['id'];
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
		$logged_user_data = $this->session->userdata("current_user");
		$currentUser = $logged_user_data['user']['id'];
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

		$logged_user_data = $this->session->userdata("current_user");
		$currentUser = $logged_user_data['user']['id'];

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

		if ($users && !$this->session->userdata('current_user')) {
			$this->session->set_flashdata("danger", "Você deve ter permissão do administrador. Faça o login.");
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

	public function conta() {
		$usuarioLogado = session();
		$dados = array("usuario" => $usuarioLogado);
		$this->load->template("usuario/conta", $dados);
	}

	public function profile() {
		$loggedUser = session();
		$userId = $loggedUser['user']['id'];	 
		$user = $this->usuarios_model->getObjectUser($userId);
		$data = array('user' => $user);
		$this->load->template("usuario/conta", $data);
	}


	public function restorePassword(){
		$validData = $this->validateDataForRestorePassword();
		
		if($validData){
			$email = $this->input->post("email");

			$user = $this->usuarios_model->getUserByEmail($email);
			$user = $this->generateNewPassword($user);
			if($user !== FALSE){
				$email = new RestorePasswordEmail($user);
				$success = $email->notify();
				
				if($success){
					$this->session->set_flashdata("success", "Email enviado com sucesso.");	
					redirect("/");
				}
				else{
					$this->session->set_flashdata("danger", "Não foi possível enviar o email. Tente novamente.");	
					redirect("usuario/restorePassword");
				}
			}
			else{
				$this->session->set_flashdata("danger", "Não foi encontrado nenhum usuário com esse email.");
				redirect("usuario/restorePassword");
			}
		}
		else{
			$this->load->template("usuario/restore_password");
		}

	}

    private function generateNewPassword($user){
        
        define('PASSWORD_LENGTH', 4); // The length of the binary to generate new password
        
        $ci =& get_instance();
        $ci->load->model('usuarios_model');

        $newPassword = bin2hex(openssl_random_pseudo_bytes(PASSWORD_LENGTH));

        // Changing the user password
        $encryptedPassword = md5($newPassword);
        $userPassword = $encryptedPassword;
        $temporaryPassword = TRUE;

        $id = $user->getId();
        $this->usuarios_model->updatePassword($id, $userPassword, $temporaryPassword);

        $user = new User($id, $user->getName(), FALSE, $user->getEmail(), FALSE, $newPassword, FALSE);

        return $user;
    }


	private function validateDataForRestorePassword(){

		$this->load->library("form_validation");
		$this->form_validation->set_rules("email", "E-mail", "required|valid_email");
		$this->form_validation->set_error_delimiters("<p class='alert-danger'>", "</p>");
		$success = $this->form_validation->run();

		return $success;
	}

	public function changePassword(){

		$success = $this->validatePasswordField();

		if ($success) {

			$password = md5($this->input->post("password"));
			$confirmPassword = md5($this->input->post("confirm_password"));

			$isValidPassword = $this->verifyIfPasswordsAreEquals($password, $confirmPassword);
			if($isValidPassword){

				$session = $this->session->userdata("current_user");
				
				$userId = $session['user']['id'];
				$temporaryPassword = FALSE;

				$isUpdated = $this->usuarios_model->updatePassword($userId, $password, $temporaryPassword);

				if($isUpdated){
					$this->session->set_flashdata("success", "Senha alterada com sucesso.");
					redirect('/');
				}
				else{
					$this->session->set_flashdata("danger", "Não foi possível alterar a senha. Tente novamente.");
					redirect('usuario/changePassword');
				}
			}
			else{
				$this->session->set_flashdata("danger", "As senhas devem ser iguais.");
				redirect('usuario/changePassword');
			}
		}
		else{
			
			$this->load->template('usuario/change_password');
		}
	}

	public function validatePasswordField(){
		
		$this->load->library("form_validation");
		$this->form_validation->set_rules("password", "Digite sua nova senha", "required");
		$this->form_validation->set_rules("confirm_password", "Confirme sua nova senha", "required");
		$this->form_validation->set_error_delimiters("<p class='alert-danger'>", "</p>");
		$success = $this->form_validation->run();

		return $success;

	}

	/**
		* Verify if password and confirm password are equals
		* @param: password: Receive the password
		* @param: confirmPassword: Receive the confirm password
	*/
	public function verifyIfPasswordsAreEquals($password, $confirmPassword){

		if ($password == $confirmPassword){
			$validPassword = TRUE;
		}
		else{
			$validPassword = FALSE;
		}

		return $validPassword;
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
			$usuarioExiste = $this->usuarios_model->buscaPorLoginESenha($login);

			if ($usuarioExiste) {
				$this->session->set_flashdata("danger", "Usuário já existe no sistema.");
				redirect("usuario/formulario");
			} else {
				$this->usuarios_model->salva($usuario);
				$this->usuarios_model->saveGroup($usuario, $group);
				$this->session->set_flashdata("success", "Usuário \"{$usuario['login']}\" cadastrado com sucesso");
				redirect("/");
			}
		} else {
			$userGroups = $this->getAllowedUserGroupsForFirstRegistration();

			$data = array('user_groups' => $userGroups);
			$this->load->template("usuario/formulario", $data);
		}
	}

	public function updateProfile(){
		
		$this->load->library("form_validation");
		$this->form_validation->set_rules("nome", "Nome", "trim|xss_clean|callback__alpha_dash_space");
		$this->form_validation->set_rules("email", "E-mail", "valid_email");
		$this->form_validation->set_rules("home_phone", "Telefone Residencial", "required|alpha_dash");
		$this->form_validation->set_rules("cell_phone", "Telefone Celular", "required|alpha_dash");
		$this->form_validation->set_error_delimiters("<p class='alert-danger'>", "</p>");
		$success = $this->form_validation->run();


		if ($success) {
			$user = $this->getAccountForm();
			$updated = $this->usuarios_model->update($user);

			$sessionData = $this->getNewSessionData($user);

			if ($updated) {
				$this->session->set_userdata('current_user', $sessionData);
				$this->session->set_flashdata("success", "Os dados foram alterados");
			} 
			else if (!$updated){
				$this->session->set_flashdata("danger", "Os dados não foram alterados");
			}

			redirect('usuario/profile');
		} 
		else {
			$this->load->template("usuario/conta");
		}
	}


	public function altera() {
		$usuarioLogado = session();

		$this->load->library("form_validation");
		$this->form_validation->set_rules("nome", "Nome", "trim|xss_clean|callback__alpha_dash_space");
		$this->form_validation->set_rules("email", "E-mail", "valid_email");
		$this->form_validation->set_error_delimiters("<p class='alert-danger'>", "</p>");
		$success = $this->form_validation->run();

		if ($success) {
			$usuario = $this->getAccountForm($usuarioLogado);

			$this->load->model('usuarios_model');
			$alterado = $this->usuarios_model->altera($usuario);

			if ($alterado && $usuarioLogado != $usuario) {
				$this->session->set_userdata('current_user', $usuario);
				$this->session->set_flashdata("success", "Os dados foram alterados");
			} else if (!$alterado){
				$this->session->set_flashdata("danger", "Os dados não foram alterados");
			}

			redirect('usuario/conta');
		} else {
			$this->load->template("usuario/conta");
		}
	}

	public function remove() {
		$usuarioLogado = session();
		$this->load->model("usuarios_model");
		if ($this->usuarios_model->remove($usuarioLogado)) {
			$this->session->unset_userdata('current_user');
			$this->session->set_flashdata("success", "Usuário \"{$usuarioLogado['user']['login']}\" removido");
			redirect("login");
		} else {
			$dados = array('usuario' => session());
			$this->load->template("usuario/conta", $dados);
		}

	}

	public function getUserByName($userName){

		$this->load->model('usuarios_model');

		$foundUser = $this->usuarios_model->getUserByName($userName);

		return $foundUser;
	}

	public function getUsersOfGroup($idGroup, $name = FALSE){

		$this->load->model('usuarios_model');

		$groups = $this->usuarios_model->getUsersOfGroup($idGroup, $name);

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

	private function getAccountForm() {
		
		$id = $this->input->post("id");
		$name = $this->input->post("name");
		$email = $this->input->post("email");
		$homePhone = $this->input->post("home_phone");
		$cellPhone = $this->input->post("cell_phone");
		$password = md5($this->input->post("password"));
		$new_password = md5($this->input->post("new_password"));
		$blank_password = 'd41d8cd98f00b204e9800998ecf8427e';


		$user = $this->usuarios_model->getObjectUser($id);
		$login = $user->getLogin();

		if ($new_password != $blank_password && $password != $user->getPassword()) {
			$this->session->set_flashdata("danger", "Senha atual incorreta");
			redirect("usuario/profile");
		} 
		else if ($new_password == $blank_password) {
			$new_password = $user->getPassword();
		}

		if (empty($name)) {
			$name = $user->getName();
		}

		if (empty($email)) {
			$email = $user->getEmail();
		}
		
		if (empty($homePhone)) {
			$homePhone = $user->getHomePhone();
		}
		
		if (empty($cellPhone)) {
			$cellPhone = $user->getCellPhone();
		}
		
		$user = new User($id, $name, $cpf, $email, $login, $new_password, FALSE, $homePhone, $cellPhone);
	
		return $user;
	}

	private function getNewSessionData($user){

		$sessionData = session();
		$sessionData['user']['id'] = $user->getId();
		$sessionData['user']['name'] = $user->getName();
		$sessionData['user']['email'] = $user->getEmail();
		$sessionData['user']['login'] = $user->getLogin();
		$sessionData['user']['password'] = $user->getPassword();
		
		return $sessionData;
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
