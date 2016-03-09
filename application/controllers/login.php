<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once("usuario.php");
require_once(APPPATH."/exception/LoginException.php");
require_once(APPPATH."/controllers/security/session/SessionManager.php");

class Login extends CI_Controller {

	public function index() {

		$program = new Program();
		$data = $program->getInformationAboutPrograms();

		$this->load->template('login/index', $data);
	}

	public function autenticar() {

		$login = $this->input->post("login");
		$password = $this->input->post("senha");

		$this->load->model("usuarios_model");

		$session = SessionManager::getInstance();
		try{

			$userController = new Usuario();
			$user = $userController->validateUser($login, $password);

			if($user !== FALSE){

				$session->login($user);

				redirect('/');

			}else{
				$authenticationStatus = "danger";
				$authenticationMessage = "Ocorreu um erro ao carregar os dados. Tente Novamente.";
				$session->showFlashMessage($authenticationStatus, $authenticationMessage);
				redirect('/');
			}

		}catch(LoginException $caughtException){
			$authenticationStatus = "danger";
			$authenticationMessage = $caughtException->getMessage();
			$session->showFlashMessage($authenticationStatus, $authenticationMessage);
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
			$session = SessionManager::getInstance();
			$session->showFlashMessage($statusLogout, $messageToDisplay);

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
		//$this->session->unset_userdata("current_user", $usuario);
		//$this->session->sess_destroy();
		SessionManager::getInstance()->logout();
		redirect($pathToRedirect);
	}
}