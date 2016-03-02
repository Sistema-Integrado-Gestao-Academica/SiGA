<?php

require_once(APPPATH."/security/PermissionException.php");

class Permission{

    const INVALID_ID = "O ID da permissão informado é inválido. O ID deve ser maior que zero.";
    const INVALID_NAME = "O nome da permissão deve ser uma String.";
    const INVALID_FUNCIONALITY = "A funcionalidade da permissão deve ser uma String.";

    private $id;
    private $name;
    private $functionality;

    public function __construct($id = FALSE, $name = FALSE, $functionality = FALSE){

        $this->setId($id);
        $this->setName($name);
        $this->setFuncionality($functionality);
    }

    private function setId($id){

        // Id must be a number or a string number
        if(is_numeric($id)){
            // Id must be greater than zero
            if($id > 0){
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
            $this->name = $name;
        }else{
            throw new PermissionException(self::INVALID_NAME);
        }
    }

    private function setFuncionality($functionality){
        if(is_string($functionality)){
            $this->functionality = $functionality;
        }else{
            throw new PermissionException(self::INVALID_FUNCIONALITY);
        }
    }
}