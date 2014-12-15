<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Usuario extends CI_Controller {
	
	public function formulario() {
		$this->load->model('usuarios_model');
		$usuarios = $this->usuarios_model->buscaTodos();

		if ($usuarios && !$this->session->userdata('usuario_logado')) {
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
		$usuarioLogado = autoriza();
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
			$tipo  = $this->input->post("userType");
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
				redirect("usuario/formulario_entrada");
			} else {
				$this->usuarios_model->salva($usuario);
				$this->usuarios_model->saveType($usuario, $tipo);
				$this->session->set_flashdata("success", "Usuário \"{$usuario['login']}\" cadastrado com sucesso");
				redirect("/");
			}
		} else {
			$this->load->template("usuario/formulario_entrada");
		}
	}

	public function altera() {
		$usuarioLogado = autoriza();

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
				$this->session->set_userdata('usuario_logado', $usuario);
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
		$usuarioLogado = autoriza();
		$this->load->model("usuarios_model");
		if ($this->usuarios_model->remove($usuarioLogado)) {
			$this->session->unset_userdata('usuario_logado');
			$this->session->set_flashdata("success", "Usuário \"{$usuarioLogado['user']['login']}\" removido");
			redirect("login");
		} else {
			$dados = array('usuario' => autoriza());
			$this->load->template("usuario/conta", $dados);
		}
		
	}

	/**
	 * Get all the user types from database into an array.
	 * @return An array with all user types on database as id => type_name
	 */
	public function getUserTypes(){
		
		// $usuarioLogado = autoriza();
		$this->load->model("usuarios_model");
		$user_types = $this->usuarios_model->getAllUserTypes();
		
		$user_types_to_array = $this->turnUserTypesToArray($user_types);

		return $user_types_to_array;
	}
	
	public function getAllUsers(){
		
		$this->load->model('usuarios_model');
		$users = $this->usuarios_model->buscaTodos();
		$form_users = $this->turnUsersToArray($users);
		
		return $form_users;
	}
	
	/**
	  * Join the id's and names of user types into an array as key => value.
	  * Used to the user type form
	  * @param $user_types - The array that contains the tuples of user_type
	  * @return An array with the id's and user types names as id => user_type_name
	  */
	private function turnUserTypesToArray($user_types){
		// Quantity of user types registered
		$quantity_of_user_types = sizeof($user_types);

		for($cont = 0; $cont < $quantity_of_user_types; $cont++){
			$keys[$cont] = $user_types[$cont]['id_type'];
			$values[$cont] = $user_types[$cont]['type_name'];
		}

		$form_user_types = array_combine($keys, $values);

		return $form_user_types;
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