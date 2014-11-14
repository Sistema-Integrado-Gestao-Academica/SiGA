<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {

	public function index() {
		$this->load->template('login/index');
	}

	public function autenticar() {
		$this->load->model("usuarios_model");
		$login = $this->input->post("login");
		$senha = $this->input->post("senha");
		$usuario = $this->usuarios_model->buscaPorLoginESenha($login, $senha);
		$tipo_usuario = $this->usuarios_model->getUserType($usuario['id']);
		
		$userData = array('user'=>$usuario,'user_type'=>$tipo_usuario);
		
		if ($userData) {
			$this->session->set_userdata("usuario_logado", $userData);
		} else {
			$this->session->set_flashdata("danger", "Usuário ou senha inválida");
		}

		redirect('/');
	}

	public function logout() {
		$this->session->unset_userdata("usuario_logado", $usuario);
		$this->session->set_flashdata("success", "Usuário deslogado");
		redirect('/');
	}
}