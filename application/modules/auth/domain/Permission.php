<?php

require_once(MODULESPATH."auth/exception/PermissionException.php");

class Permission{

    const INVALID_ID = "O ID da permissão informado é inválido. O ID deve ser maior que zero.";
    const INVALID_NAME = "O nome da permissão deve ser uma String não vazia.";
    const INVALID_FUNCIONALITY = "A funcionalidade da permissão deve ser uma String não vazia e sem espaços em branco.";

    const MINIMUN_ID = 1;

    private $id;
    private $name;
    private $functionality;

    public function __construct($id = FALSE, $name = FALSE, $functionality = FALSE){

        $this->setId($id);
        $this->setName($name);
        $this->setFuncionality($functionality);
    }

    // Setters
    private function setId($id){

        // Id must be a number or a string number
        if(is_numeric($id)){
            // Id must be greater than zero
            if($id >= self::MINIMUN_ID){
                $this->id = $id;
            }else{
                throw new PermissionException(self::INVALID_ID);
            }
        }else{
            throw new PermissionException(self::INVALID_ID);
        }
    }

    private function setName($name){
        if(is_string($name)){
            if(!empty($name)){
                $this->name = $name;
            }else{
                throw new PermissionException(self::INVALID_NAME);
            }
        }else{
            throw new PermissionException(self::INVALID_NAME);
        }
    }

    private function setFuncionality($functionality){
        if(is_string($functionality)){

            if(!empty($functionality)){
                $hasBlankSpaces = strpos($functionality, " ") !== FALSE;
                if(!$hasBlankSpaces){
                    $this->functionality = $functionality;
                }else{
                    throw new PermissionException(self::INVALID_FUNCIONALITY);
                }
            }else{
                throw new PermissionException(self::INVALID_FUNCIONALITY);
            }
        }else{
            throw new PermissionException(self::INVALID_FUNCIONALITY);
        }
    }

    // Getters
    public function getId(){
        return $this->id;
    }

    public function getName(){
        return $this->name;
    }

    public function getFunctionality(){
        return $this->functionality;
    }
}