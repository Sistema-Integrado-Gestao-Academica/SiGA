<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class UserActivation extends MX_Controller {

	const MODEL_NAME = "auth/useractivation_model";

	public function __construct(){
		parent::__construct();
		$this->load->model(self::MODEL_NAME, "activation_model");
		$this->load->model("usuarios_model");	
	}

	public function generateActivation($user){
		$alreadyExists = TRUE;
		while($alreadyExists){
			// Generates a cryptographically secure random string as activation
			$activation = bin2hex(openssl_random_pseudo_bytes(20));
			$alreadyExists = $this->activation_model->activationExists($activation);
		}

		$saved = $this->activation_model->saveActivation($user, $activation);

		if(!$saved){
			$activation = FALSE;
		}

		return $activation;
	}

	public function confirm(){

		$activationKey = $this->input->get("k");
		$encryptedUserId = $this->input->get("u");
		$initializationVector = $this->input->get("i");

		$this->load->helper("useractivation");

		$userId = openssl_decrypt($encryptedUserId, "AES128", $activationKey, $options = 0, $initializationVector);
		$confirmed = $this->activation_model->confirmRegister($userId, $activationKey);

		if($confirmed){
			$status = "success";
			$message = "Cadastro confirmado com sucesso!";
		}else{
			$status = "danger";
			$message = "O link informado já foi utilizado e está inválido. Não foi possível confirmar o seu cadastro.";
		}

		$this->session->set_flashdata($status, $message);
		redirect("/");
	}

	public function resentEmail(){

		$userId = $this->input->post('id');
		$success = $this->validateData($userId);

		if($success){
			$user = $this->usuarios_model->getUserById($userId);
			$this->activation_model->deleteUserActivation($userId);
			$activation = $this->generateActivation($user);

			$this->load->helper("useractivation");
			$message = sendConfirmationEmail($user, $activation);
	
			$this->session->set_flashdata($message['status'], $message['message']);
			redirect('/');
		}
		else{
			$status = "danger";
			$message = "Não foi possível reenviar o email. Verifique se você colocou a senha e o email informados no cadastro.";

			$this->session->set_flashdata($status, $message);
			redirect("reconfirm_register/{$userId}");
		}
	}

	private function validateData($userId){
		
		$success = $this->validateResentEmailFields();

		if($success){
			$email = $this->input->post('email');
			$password = $this->input->post('password');

			$dataIsOk = $this->usuarios_model->verifyEmailAndPassword($userId, $email, $password);			
		}
		else{
			$dataIsOk = FALSE;
		}

		return $dataIsOk;
	}
	
	private function validateResentEmailFields(){
		$this->load->library("form_validation");
		$this->form_validation->set_rules("email", "E-mail", "required|valid_email");
		$this->form_validation->set_rules("password", "Senha", "required");
		$this->form_validation->set_error_delimiters("<p class='alert-danger'>", "</p>");
		$success = $this->form_validation->run();

		return $success;
	}
	
	public function reconfirmRegister($userId){

		$this->load->model("usuarios_model", "user_model");

		$user = $this->user_model->getUserById($userId);

		$userIsActive = $user['active'] == 1;

		if(!$userIsActive){

			$data = array(
				'user' => $user
			);

			$this->load->template("auth/user/resent_email", $data);
		}else{

			$status = "danger";
			$message = "Cadastro já confirmado, {$user['login']}.";

			$this->session->set_flashdata($status, $message);
			redirect("/");
		}
	}

	public function cancelRegister(){
		
		$userId = $this->input->post('id');
		$login = $this->input->post('login');

		$correctPassword = $this->verifyUserPassword($login);

		if($correctPassword){
			// Starting transaction
			$this->db->trans_start();
			
			$activationDeleted = $this->activation_model->deleteUserActivation($userId);
			$userDeleted = $this->usuarios_model->deleteUserById($userId);
			
			// Finishing transaction
			$this->db->trans_complete();

			$transaction_status = $this->db->trans_status();

			if($transaction_status === FALSE){
				$status = "danger";
				$message = "Não foi possível cancelar o cadastro.";

				$this->session->set_flashdata($status, $message);
				redirect("reconfirm_register/{$userId}");
			}
			else{
				$status = "success";
				$message = "Cadastro cancelado com sucesso.";

				$this->session->set_flashdata($status, $message);
				redirect("/");
			}

		}
		else{
			$status = "danger";
			$message = "Senha incorreta.";

			$this->session->set_flashdata($status, $message);
			redirect("reconfirm_register/{$userId}");
		}
	}

	private function verifyUserPassword($login){

		$this->load->library("form_validation");
		$this->form_validation->set_rules("password", "Senha", "required");
		$this->form_validation->set_error_delimiters("<p class='alert-danger'>", "</p>");
		$success = $this->form_validation->run();

		if($success){
			$password = $this->input->post("password");
			$correctPassword = $this->usuarios_model->checkPasswordForThisLogin($password, $login);
		}
		else{
			$correctPassword = FALSE;
		}

		return $correctPassword;

	}
}
