<?php

require_once APPPATH."/exception/SelectionProcessException.php";
require_once "ProcessSettings.php";

abstract class SelectionProcess{
	
	const INVALID_NAME = "O nome do edital não pode estar em branco.";
	const INVALID_COURSE = "Um processo seletivo deve estar vinculado à algum curso de um programa.";
	const INVALID_ID = "O ID do processo seletivo deve ser um número maior que zero.";
	const INVALID_SETTINGS = "Configurações inválidas para o processo seletivo.";

	const MIN_ID = 1;

	private $id;
	private $name;

	// Foreign Key from Course. Course id
	private $course;

	protected $settings;

	public function __construct($course = FALSE, $name = "", $id = FALSE){
		$this->setCourse($course);
		$this->setName($name);
		$this->setId($id);
	}

	public function addSettings($settings){

		if(is_object($settings) && get_class($settings) === ProcessSettings::class && !is_null($settings)){
			$this->settings = $settings;
		}else{
			throw new SelectionProcessException(self::INVALID_SETTINGS);
		}
	}

	private function setName($name){
		
		if(!empty($name)){

			$this->name = $name;
		}else{
			throw new SelectionProcessException(self::INVALID_NAME);
		}
	}

	private function setCourse($course){

		if($course !== FALSE){
			if(!is_nan((double) $course) && $course > 0){
				$this->course = $course;
			}else{
				throw new SelectionProcessException(self::INVALID_COURSE);
			}
		}else{
			throw new SelectionProcessException(self::INVALID_COURSE);
		}
	}

	private function setId($id){

		if($id !== FALSE){

			if(!is_nan((double) $id) && $id > 0){
				$this->id = $id;
			}else{
				throw new SelectionProcessException(self::INVALID_ID);
			}
		}else{
			//If the ID is FALSE, is because is a new object, not coming from DB
			$this->id = $id;
		}
	}

	public function getName(){
		return $this->name;
	}

	public function getCourse(){
		return $this->course;
	}

	public function getId(){
		return $this->id;
	}

	public function getSettings(){
		return $this->settings;
	}
}