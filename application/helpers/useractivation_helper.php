<?php

require_once(APPPATH."/controllers/useractivation.php");
require_once(APPPATH."/data_types/notification/emails/ConfirmSignUpEmail.php");

function sendConfirmationEmail($user, $activation){

	$id = $user['id'];
	$name = $user['name'];
	$userEmail = $user['email'];
	$user = new User($id, $name, FALSE, $userEmail);

	$email = new ConfirmSignUpEmail($user, $activation);

	$sent = $email->notify();


	$message = array();
	if($sent){
		$message['status'] = "success";
		$message['message'] = "{$name}, um email foi enviado, para {$userEmail}, para você confirmar seu cadastro no sistema.";
	}
	else{
		$message['status'] = "danger";
		$message['message'] = "{$name}, não foi possível enviar o email para você confirmar seu cadastro no sistema. Cheque o email informado e tente novamente.";
	}

	return $message;
}
