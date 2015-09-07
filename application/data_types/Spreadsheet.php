<?php

include APPPATH."/phpexcel/PHPExcel.php";

class Spreadsheet{
	
	private $userType;
	private $legalSupport;

	// Finantial source identification
	private $resourseSource;
	private $costCenter;
	private $dotationNote;
	
	// User identification attributes
	private $name;
	private $id;
	private $pisPasep;
	private $cpf;
	private $enrollmentNumber;
	private $arrivalInBrazil;
	private $phone;
	private $address;
	private $projectDenomination;
	private $bank;
	private $agency;
	private $accountNumber;

	// Propose data
	private $totalValue;
	private $period;
	private $weekHours;
	private $weeks;
	private $totalHours;
	private $serviceDescription;


	public function __construct($userType, $legalSupport, $resourseSource, $costCenter, $dotationNote, $name,
		$id, $pisPasep, $cpf, $enrollmentNumber, $arrivalInBrazil, $phone, $address, $projectDenomination, $bank,
		$agency, $accountNumber, $totalValue, $period, $weekHours, $weeks, $totalHours, $serviceDescription){

		$this->userType = $userType;
		$this->legalSupport = $legalSupport;

		$this->resourseSource = $resourseSource;
		$this->costCenter = $costCenter;
		$this->dotationNote = $dotationNote;
		
		$this->name = $name;
		$this->id = $id;
		$this->pisPasep = $pisPasep;
		$this->cpf = $cpf;
		$this->enrollmentNumber = $enrollmentNumber;
		$this->arrivalInBrazil = $arrivalInBrazil;
		$this->phone = $phone;
		$this->address = $address;
		$this->projectDenomination = $projectDenomination;
		$this->bank = $bank;
		$this->agency = $agency;
		$this->accountNumber = $accountNumber;

		$this->totalValue = $totalValue;
		$this->period = $period;
		$this->weekHours = $weekHours;
		$this->weeks = $weeks;
		$this->totalHours = $totalHours;
		$this->serviceDescription = $serviceDescription;
	}

/* Getters */

	public function userType(){
		return $this->userType;
	}

	public function legalSupport(){
		return $this->legalSupport;
	}

	public function resourseSource(){
		return $this->resourseSource;
	}

	public function costCenter(){
		return $this->costCenter;
	}

	public function dotationNote(){
		return $this->dotationNote;
	}

	public function name(){
		return $this->name;
	}

	public function id(){
		return $this->id;
	}

	public function pisPasep(){
		return $this->pisPasep;
	}

	public function cpf(){
		return $this->cpf;
	}

	public function enrollmentNumber(){
		return $this->enrollmentNumber;
	}

	public function arrivalInBrazil(){
		return $this->arrivalInBrazil;
	}

	public function phone(){
		return $this->phone;
	}

	public function address(){
		return $this->address;
	}

	public function projectDenomination(){
		return $this->projectDenomination;
	}

	public function bank(){
		return $this->bank;
	}

	public function agency(){
		return $this->agency;
	}

	public function accountNumber(){
		return $this->accountNumber;
	}

	public function totalValue(){
		return $this->totalValue;
	}

	public function period(){
		return $this->period;
	}

	public function weekHours(){
		return $this->weekHours;
	}

	public function weeks(){
		return $this->weeks;
	}

	public function totalHours(){
		return $this->totalHours;
	}

	public function serviceDescription(){
		return $this->serviceDescription;
	}
/**/
}