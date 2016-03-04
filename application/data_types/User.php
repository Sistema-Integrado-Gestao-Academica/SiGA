<?php

require_once(APPPATH."/exception/UserException.php");
require_once(APPPATH."/data_types/security/Group.php");

class User{

    const INVALID_ID = "O ID do usuário informado é inválido. O ID deve ser maior que zero.";
    const INVALID_NAME = "O nome do usuário informado é inválido. Deve conter apenas caracteres alfabéticos e espaços em branco.";

    const MINIMUN_ID = 1;

    private $id;
    private $name;
    private $cpf;
    private $email;
    private $login;
    private $password;
    private $groups;

    public function __construct($id = FALSE, $name = FALSE, $cpf = FALSE, $email = FALSE, $login = FALSE, $password = FALSE, $groups = FALSE){

        $this->setId($id);
        $this->setName($name);
        $this->setCpf($cpf);
        $this->setEmail($email);
        $this->setLogin($login);
        $this->setPassword($password);
        $this->setGroups($groups);
    }

    public function addGroup($group){
        if(is_object($group)){
            if(get_class($group) === Group::class){
                $this->groups[] = $group;
            }else{
                throw new UserException(self::INVALID_GROUP);
            }
        }else{
            throw new UserException(self::INVALID_GROUP);
        }
    }

    // Setters
    private function setId($id){

        // Id must be a number or a string number
        if(is_numeric($id)){
            // Id must be greater than zero
            if($id >= self::MINIMUN_ID){
                $this->id = $id;
            }else{
                throw new UserException(self::INVALID_ID);
            }
        }else{
            throw new UserException(self::INVALID_ID);
        }
    }

    private function setName($name){

        if(is_string($name)){
            if(!empty($name)){

                // Split the first, middle and last name into a array
                $nameParts = explode(" ", $name);

                $nameIsOk = TRUE;
                foreach($nameParts as $part){

                    // Check if is only letters
                    if(ctype_alpha($part)){
                        continue;
                    }else{
                        $nameIsOk = FALSE;
                        break;
                    }
                }

                if($nameIsOk){
                    $this->name = $name;
                }else{
                    throw new UserException(self::INVALID_NAME);
                }

            }else{
                throw new UserException(self::INVALID_NAME);
            }
        }else{
            throw new UserException(self::INVALID_NAME);
        }
    }

    private function setCpf($cpf){
        $this->cpf = $cpf;
    }

    private function setEmail($email){
        $this->email = $email;
    }

    private function setLogin($login){
        $this->login = $login;
    }

    private function setPassword($password){
        $this->password = $password;
    }

    private function setGroups($groups){
        if($groups !== FALSE){
            $this->groups = $groups;
        }else{
            $this->groups = array();
        }
    }

    // Getters
    public function getId(){
        return $this->id;
    }

    public function getName(){
        return $this->name;
    }

    public function getCpf(){
        return $this->cpf;
    }

    public function getEmail(){
        return $this->email;
    }

    public function getLogin(){
        return $this->login;
    }

    public function getPassword(){
        return $this->password;
    }

    public function getGroups(){
        return $this->groups;
    }
}