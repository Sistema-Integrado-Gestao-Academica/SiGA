<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {

	public function index() {
		$usuario = array(
			'nome' => 'admin',
			'email' => 'admin@gmail.com',
			'login' => 'admin',
			'senha' => 'random'
		);

		$this->session->set_userdata("current_user", $usuario);
		redirect('/');
	}

}
