<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once "Usuario.php";

class UserActivation extends CI_Controller {

	const MODEL_NAME = "useractivation_model";

	public function __construct(){
		parent::__construct();
		$this->load->model(self::MODEL_NAME, "activation_model");
	}

	public function generateActivation($user){

		$alreadyExists = TRUE;
		while($alreadyExists){
			// Generates a cryptographically secure ramdon string as activation
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

		$userId = openssl_decrypt($encryptedUserId, "AES128", $activationKey);

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

	public function resentEmail($userId){

		$user = new Usuario();

		$user = $user->getUserById($userId);

		$userIsActive = $user['active'] == 1;

		if(!$userIsActive){

			$data = array(
				'user' => $user
			);

			$this->load->template("usuario/resent_email", $data);
		}else{

			$status = "danger";
			$message = "Cadastro já confirmado, {$user['login']}.";

			$this->session->set_flashdata($status, $message);
			redirect("/");
		}
	}
}
