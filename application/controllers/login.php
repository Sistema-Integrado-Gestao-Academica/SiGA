<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH."/exception/LoginException.php");

class Login extends CI_Controller {

	public function index() {
		$this->load->template('login/index');
	}

	public function autenticar() {
		
		$login = $this->input->post("login");
		$password = $this->input->post("senha");

		try{

			$this->load->model("usuarios_model");
			$user = $this->usuarios_model->validateUser($login, $password);
			
			if(sizeof($user) > 0){
				//$user_type = $this->usuarios_model->getUserType($user['id']);
				
				$this->load->model("module_model");
				$registered_permissions = $this->module_model->getUserPermissions($user['id']);
				$registered_permissions = array_combine($registered_permissions['route'], $registered_permissions['name']);

				$registered_groups = $this->module_model->getUserGroups($user['id']);

				$userData = array(
					'user' => $user,
					//'user_type' => $user_type,
					'user_permissions' => $registered_permissions,
					'user_groups' => $registered_groups
				);

				$this->session->set_userdata("current_user", $userData);
				redirect('/');
				
			}else{
				$authenticationStatus = "danger";
				$authenticationMessage = "Ocorreu um erro ao carregar os dados. Tente Novamente.";
				$this->session->set_flashdata($authenticationStatus, $authenticationMessage);
				redirect('/');
			}

		}catch(LoginException $caughtException){
			$authenticationStatus = "danger";
			$authenticationMessage = $caughtException->getMessage();
			$this->session->set_flashdata($authenticationStatus, $authenticationMessage);
			redirect('/');
		}
		
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
		$this->session->unset_userdata("current_user", $usuario);
		redirect($pathToRedirect);
	}
}