<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once('course.php');
require_once('module.php');
require_once('masterdegree.php');
require_once('doctorate.php');

class Usuario extends CI_Controller {
	
	public function student_index(){
		$logged_user_data = $this->session->userdata("current_user");
		$userId = $logged_user_data['user']['id'];

		$this->load->model('usuarios_model');
		$userStatus = $this->usuarios_model->getUserStatus($userId);

		$userStatus = array(
			'status' => $userStatus
		);

		$this->loadStudentTemplateSafely('usuario/student_home', $userStatus);
	}

	public function guest_index(){

	}

	public function secretary_index(){

		$courses = $this->loadCourses();
		
		$courseData = array(
			'courses' => $courses['courses'],
			'masterDegrees' => $courses['masterDegrees'],
			'doctorates' => $courses['doctorates']
		);

		// Fazer o loadTemplateSafelly()
		$this->load->template('usuario/secretary_home', $courseData);

	}

	private function loadCourses(){
		
		define("ACADEMIC_PROGRAM", "academic_program");
		define("PROFESSIONAL_PROGRAM", "professional_program");

		$logged_user_data = $this->session->userdata("current_user");
		$currentUser = $logged_user_data['user']['id'];

		$this->load->model('course_model');
		$allCourses = $this->course_model->getAllCourses();

		$masterDegrees = array();
		$doctorates = array();
	
		for($i = 0; $i < sizeof($allCourses); $i++){

			$currentCourse = $allCourses[$i];
			$currentCourseId = $currentCourse['id_course'];

			$userHasSecretaryForThisCourse = $this->checkIfUserHasSecretaryOfThisCourse($currentCourseId, $currentUser);
			
			if($userHasSecretaryForThisCourse){

				$currentCourseType = $currentCourse['course_type'];

				switch($currentCourseType){
					case ACADEMIC_PROGRAM:

						$masterDegree = new MasterDegree();
						$registeredMasterDegree = $masterDegree->getMasterDegreeByCourseId($currentCourseId);

						$doctorate = new Doctorate();
						$registeredDoctorate = $doctorate->getRegisteredDoctorateForCourse($currentCourseId);

						if($registeredMasterDegree !== FALSE){
							$masterDegrees[$currentCourseId] = $registeredMasterDegree;
						}

						if($registeredDoctorate !== FALSE){
							$doctorates[$currentCourseId] = $registeredDoctorate;
						}

						break;

					case PROFESSIONAL_PROGRAM:
						
						$masterDegree = new MasterDegree();
						$registeredMasterDegree = $masterDegree->getMasterDegreeByCourseId($currentCourseId);

						if($registeredMasterDegree !== FALSE){
							$masterDegrees[$currentCourseId] = $registeredMasterDegree;
						}

						break;

					default:

						break;
				}
			}else{

				// In this case this course does not belong to the current user secretary
				unset($allCourses[$i]);
			}
		}

		$courses = array(
			'courses' => $allCourses,
			'masterDegrees' =>$masterDegrees,
			'doctorates' => $doctorates
		);

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
		$usuarios = $this->usuarios_model->buscaTodos();

		if ($usuarios && !$this->session->userdata('current_user')) {
			$this->session->set_flashdata("danger", "Você deve ter permissão do administrador. Faça o login.");
			redirect('login');
		} else {
			$this->load->template("usuario/formulario");
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
			$grupo = $this->input->post("userGroup");
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
			$this->load->model("usuarios_model");
			$user_group_options = $this->usuarios_model->getAllUserGroups();
			$user_groups = array();

			foreach ($user_group_options as $ug) {
				array_push($user_groups, $ug['group_name']);
			}

			$data = array('user_groups' => $user_groups);
			$this->load->template("usuario/formulario_entrada", $data);
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
	
	public function getAllowedUserGroupsForNotLoggedRegistration(){

		$this->load->model("usuarios_model");
		$user_groups = $this->usuarios_model->getAllAllowedUserGroupsForNotLoggedRegistration();
		
		$user_groups_to_array = $this->turnUserGroupsToArray($user_groups);
		
		return $user_groups_to_array;
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

	/**
	 * Checks if the user has the permission to the student pages before loading it
	 * ONLY applicable to the student pages
	 * @param $template - The page to be loaded
	 * @param $data - The data to send along the view
	 * @return void - Load the template if the user has the permission or logout the user if does not
	 */
	private function loadStudentTemplateSafely($template, $data = array()){

		$user_has_the_permission = $this->checkUserStudentPermission();

		if($user_has_the_permission){
			$this->load->template($template, $data);
		}else{
			logoutUser();
		}
	}

	/**
	 * Check if the logged user have the permission to the student page
	 * @return TRUE if the user have the permission or FALSE if does not
	 */
	private function checkUserStudentPermission(){
		$logged_user_data = $this->session->userdata('current_user');
		$permissions_for_logged_user = $logged_user_data['user_permissions']['route'];

		$user_has_the_permission = $this->haveStudentPermission($permissions_for_logged_user);

		return $user_has_the_permission;
	}

	/**
	 * Evaluates if in a given array of permissions the student one is on it
	 * @param permissions_array - Array with the permission names
	 * @return True if there is the student permission on this array, or false if does not.
	 */
	private function haveStudentPermission($permissions_array){
		
		define("STUDENT_GROUP", 7);
		
		$arrarIsNotEmpty = is_array($permissions_array) && !is_null($permissions_array);
		
		if($arrarIsNotEmpty){
			$existsThisPermission = FALSE;
			foreach($permissions_array as $route => $permission_name){
				
				if($route === STUDENT_GROUP){
					$existsThisPermission = TRUE;
				}
			}
		}else{
			$existsThisPermission = FALSE;
		}

		return $existsThisPermission;
	}


	
}

function alpha_dash_space($str) {
	return ( ! preg_match("/^([-a-z_ ])+$/i", $str)) ? FALSE : TRUE;
}