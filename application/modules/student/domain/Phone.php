<?php

require_once(MODULESPATH."student/exception/PhoneException.php");

class Phone{
	
	// Valid number lengths
	const MIN_NUM_LENGTH = 8;
	const MAX_NUM_LENGTH = 11;

	const INVALID_NUMBER = "O número deve conter de 8 a 11 números, contando com o DDD.";

	private $number;

	public function __construct($number){
		$this->setNumber($number);
	}

	private function setNumber($number){

		if(ctype_digit($number)){
			
			$numberLength = strlen($number);
			$validNumber = $numberLength >= self::MIN_NUM_LENGTH  
							&& $numberLength <= self::MAX_NUM_LENGTH;


			if($validNumber){
				$this->number = $number;
			}else{
				throw new PhoneException(self::INVALID_NUMBER);
			}
		}else{
			throw new PhoneException(self::INVALID_NUMBER);
		}
	}

	public function getNumber(){
		return $this->number;
	}
}