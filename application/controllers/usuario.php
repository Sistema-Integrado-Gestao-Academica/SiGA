<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once('course.php');
require_once('module.php');
require_once('semester.php');
require_once('offer.php');
require_once('syllabus.php');
require_once('masterdegree.php');
require_once('doctorate.php');

class Usuario extends CI_Controller {
	
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

		$this->load->model('usuarios_model');
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
		
		$this->session->set_flashdata($status, $message);	
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
		
		$this->session->set_flashdata($status, $message);	
		redirect("usuario/listUsersOfGroup/{$idGroup}");
	}

	public function checkIfUserExists($idUser){
		
		$this->load->model('usuarios_model');

		$userExists = $this->usuarios_model->checkIfUserExists($idUser);

		return $userExists;
	}

	private function getAllUsers(){

		$this->load->model('usuarios_model');

		$allUsers = $this->usuarios_model->getAllUsers();

		return $allUsers;
	}

	public function student_index(){
		$logged_user_data = $this->session->userdata("current_user");
		$userId = $logged_user_data['user']['id'];

		$this->load->model('usuarios_model');
		$userStatus = $this->usuarios_model->getUserStatus($userId);
		$userCourse = $this->usuarios_model->getUserCourse($userId);

		$userData = array(
			'status' => $userStatus,
			'courses' => $userCourse
		);

		// On auth_helper
		loadTemplateSafelyByGroup("estudante", 'usuario/student_home', $userData);
	}

	public function guest_index(){

	}

	public function secretary_index(){

		loadTemplateSafelyByGroup("secretario",'usuario/secretary_home');
	}

	public function secretary_enrollStudent(){

		$courses = $this->loadCourses();

		$courseData = array(
			'courses' => $courses
		);

		loadTemplateSafelyByGroup("secretario",'usuario/secretary_enroll_student', $courseData);
	}

	public function secretary_offerList(){

		$semester = new Semester();
		$currentSemester = $semester->getCurrentSemester();

		// Check if the logged user have admin permission
		$group = new Module();
		$isAdmin = $group->checkUserGroup('administrador');

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

		loadTemplateSafelyByGroup("secretario",'usuario/secretary_offer_list', $data);
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
		
		loadTemplateSafelyByGroup("secretario",'usuario/secretary_course_syllabus', $data);
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
		$foundSecretary = $course->getCourseSecrecretary($courseId);
		
		if($foundSecretary !== FALSE){
			
			$secretaryUser = $foundSecretary['id_user'];

			if($secretaryUser === $userId){
				$userHasSecretary = TRUE;
			}else{
				$userHasSecretary = FALSE;
			}

		}else{
			$userHasSecretary = FALSE;
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
				$this->session->set_flashdata("danger", "Usuário já existe no sistema");
				redirect("usuario/formulario");
			} else {
				$this->usuarios_model->salva($usuario);
				$this->usuarios_model->saveGroup($usuario, $grupo);
				$this->session->set_flashdata("success", "Usuário \"{$usuario['login']}\" cadastrado com sucesso");
				redirect("/");
			}
		} else {
			$userGroups = $this->getAllowedUserGroupsForFirstRegistration();

			$data = array('user_groups' => $userGroups);
			$this->load->template("usuario/formulario", $data);
		}
	}

	public function altera() {
		$usuarioLogado = session();

		$this->load->library("form_validation");
		$this->form_validation->set_rules("nome", "Nome", "alpha");
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

	private function getRegisteredStudentsByName($userName){

		// define("STUDENT", "estudante");
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

	private function getUserByName($userName){

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
	
	public function getAllSecretaryUsers(){
		
		$this->load->model('usuarios_model');
		$users = $this->usuarios_model->getAllSecretaries();
		
		return $users;
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

	private function getAccountForm($usuarioLogado) {
		$name = $this->input->post("nome");
		$email = $this->input->post("email");
		$login = $usuarioLogado['user']['login'];
		$password = md5($this->input->post("senha"));
		$new_password = md5($this->input->post("nova_senha"));
		$blank_password = 'd41d8cd98f00b204e9800998ecf8427e';

		$this->load->model('usuarios_model');
		$user = $this->usuarios_model->busca('login', $login);

		if ($new_password != $blank_password && $password != $user['password']) {
			$this->session->set_flashdata("danger", "Senha atual incorreta");
			redirect("usuario/conta");
		} else if ($new_password == $blank_password) {
			$new_password = $user['password'];
		}

		if ($name == "") {
			$name = $user['name'];
		}

		if ($email == "") {
			$email = $user['email'];
		}

		$user = $usuarioLogado;
		$user['user']['name'] = $name;
		$user['user']['email'] = $email;
		$user['user']['password'] = $new_password;

		return $user;
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