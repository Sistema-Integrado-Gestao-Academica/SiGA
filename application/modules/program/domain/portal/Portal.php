<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Portal{

    const MIN_ID = 0;
    const ID_CANT_BE_LESS_THAN_ZERO = "O id deve ser maior que zero.";

    public function __construct($id = FALSE, $name = ""){
        $this->setId($id);
        $this->setName($name);
    }

    protected function setId($id){
        if($id >= self::MIN_ID){
            $this->id = $id;
        }
        else{
            throw new Exception(self::ID_CANT_BE_LESS_THAN_ZERO);
        }
    }

    protected function setName($name){
        $this->name = $name;
    }


    public function getId(){
        return $this->id;
    }

    public function getName(){
        return $this->name;
    }


}