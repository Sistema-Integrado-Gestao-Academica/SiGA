<?php

require_once(MODULESPATH."secretary/exception/StudentRegistrationException.php");

class StudentRegistrationOld{

    const COULDNT_GET_CURRENT_YEAR = "Não foi possível recuperar o ano atual para compor a matrícula. Contate o administrador.";
    const REGISTRATION_CANT_BE_EMPTY = "A matrícula do aluno não pode estar vazia.";
    const REGISTRATION_MUST_BE_9_NUMBERS = "A matrícula do aluno deve conter apenas 9 números.";
    const REGISTRATION_LENGTH = 9;

    private $registration;

    public function __construct($registration = FALSE){

        if($registration !== FALSE){
            $this->setRegistration($registration);
        }else{
            $this->generateRegistration();
        }
    }

    private function generateRegistration(){

        $currentYear = getCurrentYear();

        if($currentYear !== FALSE){

            // Get the last two digits of year
            $year = $currentYear[2].$currentYear[3];

            $registration = $year;

            for($i = 0; $i < 7; $i++){

                // Random number between 0 and 9.
                $randNumber = rand(0, 9);
                $registration = $registration.$randNumber;
            }

            $this->setRegistration($registration);
        }else{
            throw new StudentRegistrationException(self::COULDNT_GET_CURRENT_YEAR);
        }
    }

    private function setRegistration($registration){

        if($registration !== NULL && !empty($registration)){

            if(ctype_digit($registration)){

                if(strlen($registration) === self::REGISTRATION_LENGTH){

                    $this->registration = $registration;
                }else{

                    throw new StudentRegistrationException(self::REGISTRATION_MUST_BE_9_NUMBERS);
                }

            }else{
                throw new StudentRegistrationException(self::REGISTRATION_MUST_BE_9_NUMBERS);
            }
        }else{
            throw new StudentRegistrationException(self::REGISTRATION_CANT_BE_EMPTY);
        }
    }

    public function getFormattedRegistration(){

        $registration = $this->getRegistration();

        $year = $registration[0].$registration[1];

        $registration = substr($registration, 2, 7);

        $formattedRegistration = $year."/".$registration;

        return $formattedRegistration;
    }

    public function getRegistration(){

        return $this->registration;
    }
}


