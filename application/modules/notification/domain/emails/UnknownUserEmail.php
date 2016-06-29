<?php

require_once MODULESPATH."notification/domain/EmailNotification.php";

class UnknownUserEmail extends EmailNotification{

    const EMPTY_COURSE_NAME = "O curso não pode ser nulo";

    const UNKNOWN_USER_EMAIL_SUBJECT = "Solicitação de inscrição no curso ";

    private $course;

	public function __construct($user, $course){
        $this->setCourse($course);
        parent::__construct($user);
	}

	protected function setSubject(){

        $course = $this->getCourse();
        $this->subject = self::UNKNOWN_USER_EMAIL_SUBJECT."{$course}"; 
	}

	protected function setMessage(){ 
        
        $user = $this->user();
        $newPassword = $user->getPassword();
        $userName = $user->getName();
        $course = $this->getCourse();
        $message = "";

        if(!is_null($course) && !empty($course)){

            $message = "Olá, <b>{$userName}</b>. <br>";
            $message = $message."Sua solicitação de inscrição no curso {$course} foi recusada, pois ";
            $message = $message."a secretaria do curso não reconheceu você como aluno. <br>";
            $message = $message."Você pode entrar em contato com a secretaria ou solicitar sua inscrição novamente.<br><br>"; 
        }
        
        $this->message = $message;
    }

    private function setCourse($course){
        
        if(!is_null($course)){
            $ci =& get_instance();
            $ci->load->model("program/course_model");

            $course = $ci->course_model->getCourseName($course);
            $this->course = $course; 
        }
        else{
            throw new EmailNotificationException(self::EMPTY_COURSE_NAME);
            
        }

    }

    public function getCourse(){
        return $this->course;
    }

}