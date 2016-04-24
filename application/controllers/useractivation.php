<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

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
}
