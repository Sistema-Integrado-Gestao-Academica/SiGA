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
		$user_type = $this->usuarios_model->getUserType($usuario['id']);
		
		// Load the Module model
		$this->load->model("module_model");
		$registered_permissions = $this->module_model->getUserPermissionNames($usuario['id']);

		$userData = array('user' => $usuario,'user_type' => $user_type, 'user_permissions' => $registered_permissions);

		if ($usuario) {
			$this->session->set_userdata("usuario_logado", $userData);
		} else {
			$this->session->set_flashdata("danger", "Usuário ou senha inválida");
		}

		redirect('/');
	}
	

	/**
	 * Log out the current user on the session and redirect to a given path
	 * @param $messageToDisplay - Message to show after the logout be performed
	 * @param $statusLogout - Status of the logout (sucess or danger).
	 *	Reflect the conditions in which the logout was made.
	 * @param $path - The path to redirect after logout.
	 * @return void
	 */
	public function logout($messageToDisplay = "", $statusLogout = "success", $path = '/') {
		
		$thereIsMessage = !empty($messageToDisplay);
		if($thereIsMessage){
			$statusLogout = $this->checkStatusLogout($statusLogout);
			$this->session->set_flashdata($statusLogout, $messageToDisplay);

			// VALIDAR O PATH
			$this->unsetLoggedUserAndRedirectTo($path);
		}else{
			$this->unsetLoggedUserAndRedirectTo($path);
		}

	}

	/**
	 * Check if the statusLogout is or 'danger' or 'success'
	 * @param statusLogout - The status of the logout to validate
	 * @return "danger" if different of "danger" and "success"
	 */
	private function checkStatusLogout($statusLogout){
		if($statusLogout !== "danger" && $statusLogout !== "success"){
			$statusLogout = "danger";
		}else{
			// Nothing to do because the only two options is succes or danger
		}

		return $statusLogout;
	}

	/**
	 * Clean the session and redirect to a given path
	 * @param pathToRedirect - The path to redirect after unset the user on session
	 * @return void
	 */
	private function unsetLoggedUserAndRedirectTo($pathToRedirect){
		$this->session->unset_userdata("usuario_logado", $usuario);
		redirect($pathToRedirect);
	}
}