<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(MODULESPATH."auth/exception/LoginException.php");
require_once(MODULESPATH."auth/domain/User.php");

class Login extends MX_Controller {

	public function authenticate(){

		$login = $this->input->post("login");
		$password = $this->input->post("password");

		$this->load->model("auth/usuarios_model");

		$session = getSession();

		try{

			$this->load->model("auth/usuarios_model");
			$user = $this->usuarios_model->validateUser($login, $password);

			if($user !== FALSE){

				$userIsActive = $user->getActive();
				
				if($userIsActive){

					$session->login($user);

					$isATemporaryPassword = $this->usuarios_model->verifyIfIsTemporaryPassword($user->getId());				

					if(!$isATemporaryPassword){
						redirect('/');
					}
					else{
						redirect('auth/userController/changePassword');
					}	
					
				}else{

					$userId = $user->getId();
					$registerNotConfirmedLink = anchor("reconfirm_register/{$userId}",'clique aqui');
					$authenticationStatus = "danger";
					$authenticationMessage = "Cadastro não confirmado. Um e-mail de confirmação foi enviado para o e-mail utilizado no cadastro.
					<br> Caso não tenha recebido o e-mail, <b>{$registerNotConfirmedLink}</b>.";
					
					$session->showFlashMessage($authenticationStatus, $authenticationMessage);
					redirect('/');
				}

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
			$session = getSession();
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
		getSession()->logout();
		redirect($pathToRedirect);
	}
}