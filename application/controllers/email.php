
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH."/data_types/notification/EmailNotification.php");

class Email extends CI_Controller {


    public function __construct(){
        parent::__construct();
        $this->load->model('usuarios_model');
    }

    public function setDefaultConfiguration(){
    	
    	$mail = new PHPMailer();
        $mail->IsSMTP(); 
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = "ssl"; 
        $mail->Host = ""; 
        $mail->Port = 465; 
        $mail->Username = ""; 
        $mail->Password = ""; 
        $mail->CharSet = 'UTF-8';
        return $mail;
    }


    /**
        * Send a email for a user
        * @param $userEmail: The email address of the user
        * @param $instituteName: The name of the institute
        * @param $instituteEmail: The email address of the institute
        * @param $subject: The subject of the email
        * @param $message: The message of the email
    */
    private function sendEmailForUser($email){

        $emailSent = FALSE;
        $this->load->library("My_PHPMailer");
        $mail = $this->setDefaultConfiguration(); 
        $mail->IsHTML(true);
        $mail->Subject = $email->getSubject(); 
        $mail->Body = $email->getMessage();
        $mail->SetFrom($email->getSenderName(), $email->getSenderEmail()); 
        $mail->AddAddress($email->getReceiverName(), $email->getReceiverEmail());
        $emailSent = $mail->Send();

        return $emailSent;
    }

    /**
        Email to restore password
    */
    public function sendEmailForRestorePassword($user){ 
        
        $newPassword = $this->generateNewPassword($user);
        
        $subject = "Solicitação de recuperação de senha - SiGA"; 
        $message = "Olá, <b>{$user['name']}</b>. <br>";
        $message = $message."Esta é uma mensagem automática para a solicitação de nova senha de acesso ao SiGA. <br>";
        $message = $message."Sua nova senha para acesso é: <b>".$newPassword."</b>. <br>";
        $message = $message."Lembramos que para sua segurança ao acessar o sistema com essa senha iremos te redirecionar para a definição de uma nova senha. <br>"; 

        $email = new EmailNotification($user['id'], $user['name'], $user['email'], $subject, $message);

        $success = $this->sendEmailForUser($email);

        return $success;
    }

    private function generateNewPassword($user){
        
        define('PASSWORD_LENGTH', 4); // The length of the binary to generate new password
        
        $newPassword = bin2hex(openssl_random_pseudo_bytes(PASSWORD_LENGTH));

        // Changing the user password
        $encryptedPassword = md5($newPassword);
        $user['password'] = $encryptedPassword;
        $temporaryPassword = TRUE;
        $this->usuarios_model->updatePassword($user, $temporaryPassword);

        return $newPassword;
    }
}
