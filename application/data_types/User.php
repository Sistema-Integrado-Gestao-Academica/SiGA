<?php

require_once(APPPATH."/exception/UserException.php");

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
	private $homePhone;
	private $cellPhone;
	private $active;
	
	public function __construct($id = FALSE, $name = FALSE, $cpf = FALSE, $email = FALSE, $login = FALSE, $password = FALSE, $groups = FALSE, $homePhone = FALSE, $cellPhone = FALSE, $active = FALSE){
		$this->setId($id);
		$this->setName($name);
		$this->setCpf($cpf);
		$this->setEmail($email);
		$this->setLogin($login);
		$this->setPassword($password);
		$this->setGroups($groups);
		$this->setHomePhone($homePhone);
		$this->setCellPhone($cellPhone);
		$this->setActive($active);
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

	public function setName($name){
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
	private function setHomePhone($homePhone){
		$this->homePhone = $homePhone;
	}

	private function setCellPhone($cellPhone){
		$this->cellPhone = $cellPhone;
	}

	private function setActive($active){
		if(is_bool($active)){
			$this->active = $active;
		}else{
			if($active === "1" || $active === 1){
				$active = TRUE;
			}else{
				$active = FALSE;
			}
			$this->active = $active;
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

	public function getHomePhone(){
		return $this->homePhone;
	}

	public function getCellPhone(){
		return $this->cellPhone;
	}

	public function getActive(){
		return $this->active;
	}
}