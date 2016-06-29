<?php

require_once APPPATH."/data_types/notification/EmailNotification.php";
require_once APPPATH."/exception/EmailNotificationException.php";

class EnrolledStudentEmail extends EmailNotification{

    const NULL_COURSE = "O curso não pode ser nulo.";
    
    const STUDENT_ENROLLED_SUBJECT = "Matrícula realizada";

    private $course;

	public function __construct($user, $course){
        $this->setCourse($course);
        parent::__construct($user);
    }

    protected function setSubject(){

        $this->subject = self::STUDENT_ENROLLED_SUBJECT; 
    }

    protected function setMessage(){ 
        
        $user = $this->user();
        $course = $this->getCourse();
        $userName = $user->getName();
        $message = "";

        if(!is_null($course) && !empty($course)){
            $message = "Olá, <b>{$userName}</b>. <br>";
            $message = $message."Estamos te enviando essa mensagem para comunicar que sua matrícula no curso de: <b>".$course."</b> foi efetuada.<br>";
            $message = $message."O seu acesso ao SiGA como estudante está liberado."; 
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
            throw new EmailNotificationException(self::NULL_COURSE);
            
        }
    }

    public function getCourse(){
        return $this->course;
    }
}