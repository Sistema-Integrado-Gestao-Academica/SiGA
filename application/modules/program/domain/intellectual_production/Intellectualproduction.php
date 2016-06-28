<?php

require_once MODULESPATH."program/exception/IntellectualProductionException.php";
require_once 'ProductionType.php';

class IntellectualProduction{

	const AUTHOR_CANT_BE_NULL = "A produção deve possuir um autor";
	const TITLE_CANT_BE_NULL = "A produção deve possuir um título";
	const INVALID_YEAR = "Ano de publicação inválido";
	const INVALID_QUALIS = "Qualis inválido";
	const INVALID_TYPE = "Tipo de produção inválido";
	const INVALID_SUBTYPE = "Subtipo de produção inválido";
	const INVALID_IDENTIFIER = "Identificador inválido";

	const QUALIS_LENGTH = 2;
	// ISBN
	const MAX_IDENTIFIER_LENGTH = 13;
	// ISSN
	const MIN_IDENTIFIER_LENGTH = 8;

	private $author;
	private $title;
	private $type;
	private $year;
	private $subtype;
	private $qualis;
	private $periodic;
	private $identifier; // ISSN ou ISBN


	public function __construct($author, $title, $type = FALSE, $year = FALSE, $subtype = FALSE,
								$qualis = FALSE, $periodic = FALSE, $identifier = FALSE){


		$this->setAuthor($author);
		$this->setTitle($title);
		$this->setType($type);
		$this->setYear($year);
		$this->setSubtype($subtype);
		$this->setQualis($qualis);
		$this->setPeriodic($periodic);
		$this->setIdentifier($identifier);

	}

	private function setAuthor($author){

		if(!isEmpty($author)){

			// It is a User object
			$this->author = $author;

		}
		else{
			throw new IntellectualProductionException(self::AUTHOR_CANT_BE_NULL);
			
		}

	}


	private function setTitle($title){
		if(!isEmpty($title)){

			$this->title = $title;

		}
		else{
			throw new IntellectualProductionException(self::TITLE_CANT_BE_NULL);
			
		}
	} 

	private function setType($typeId){

		if($typeId !== FALSE){

			$typeId = (int) $typeId;
			$types = ProductionType::getTypes();

			if($typeId <= (count($types) - 1)){
				
				$type = $types[$typeId];
				$this->type = $typeId;
			}
			else{

				throw new IntellectualProductionException(self::INVALID_TYPE);
			}
		}
		else{
			$this->type = NULL;
		}		

	}

	private function setYear($year){

		if($year !== FALSE && !isEmpty($year)){

			if(is_numeric($year) && strlen($year) == 4){

				$this->year = (int) $year;

			}
			else{

				throw new IntellectualProductionException(self::INVALID_YEAR);
			}
		}
		else{
			$this->year = NULL;			
		}

	}

	private function setSubtype($subtypeId){

		if($subtypeId !== FALSE){
			
			$subtypeId = (int) $subtypeId;
			$subtypes = ProductionType::getSubtypes();
			
			if($subtypeId <= (count($subtypes) - 1)){
			
				$subtype = $subtypes[$subtypeId];
				$this->subtype = $subtypeId;
			} 
			else{
				throw new IntellectualProductionException(self::INVALID_SUBTYPE);
			}

		}
		else{
			$this->subtype = NULL;
		}

	}

	private function setQualis($qualis){
		

		if($qualis !== FALSE && !isEmpty($qualis)){

			if(strlen($qualis) == self::QUALIS_LENGTH){
				$this->qualis = $qualis;
			}
			else{
				throw new IntellectualProductionException(self::INVALID_QUALIS);
			}
		}
		else{
			$this->qualis = NULL;
		}
	}
	
	private function setPeriodic($periodic){
		if($periodic !== FALSE && !isEmpty($periodic)){
			$this->periodic = $periodic;
		}
		else{
			$this->periodic = NULL;
		}
	}

	private function setIdentifier($identifier){

		if($identifier !== FALSE && !isEmpty($identifier)){

			$validLength = strlen($identifier) == self::MAX_IDENTIFIER_LENGTH || 
							strlen($identifier) == self::MIN_IDENTIFIER_LENGTH;
			
			if(is_numeric($identifier) && $validLength){		

				$this->identifier = (int) $identifier;

			}
			else{

				throw new IntellectualProductionException(self::INVALID_IDENTIFIER);
			}
		}
		else{
			$this->identifier = NULL;			
		}
	}

	public function getTitle(){
		return $this->title;
	}

	public function getAuthor(){
		return $this->author;
	}

	public function getType(){
		return $this->type;
	}

	public function getYear(){
		return $this->year;
	}

	public function getSubtype(){
		return $this->subtype;
	}

	public function getQualis(){
		return $this->qualis;
	}

	public function getPeriodic(){
		return $this->periodic;
	}

	public function getIdentifier(){
		return $this->identifier;
	}
}