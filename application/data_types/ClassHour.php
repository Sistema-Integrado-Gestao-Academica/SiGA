<?php 

/**
 * This class represents a unique schedule of a discipline class.
 * Is considered a bidimensional array to represent all schedule possibilities 
 * with the class hours (in an interval of 2 hours each) as lines and the days of week as columns (except sunday), totaling a 9x6 matrix.
 */

require_once(APPPATH."/exception/ClassHourException.php");

class ClassHour{

	private $hour; // Hour interval of day that this class happens (References the line 'i')
	private $day; // Day of the week that this class happens (References the column 'j')
	private $local; // Local where class happens

	const MAX_HOUR = 9;
	const MIN_HOUR = 1;

	const MAX_DAY = 6;
	const MIN_DAY = 1;

	const ERR_INVALID_HOUR = "Hour out of range 1-9";
	const ERR_INVALID_DAY = "Day out of range 1-6";
	const ERR_INVALID_LOCAL = "Local of class must be a string";

	public function __construct($hour = 0, $day = 0, $local = ""){

		try{

			$this->setHour($hour);
			$this->setDay($day);
			$this->setlocal($local);
		}catch(ClassHourException $caughException){
			 throw $caughException;
		}
	}

	private function setHour($hour){

		$hourIsOk = $hour >= self::MIN_HOUR && $hour <= self::MAX_HOUR;
		if($hourIsOk){
			$this->hour = $hour;
		}else{
			throw new ClassHourException(self::ERR_INVALID_HOUR);
		}
	}

	private function setDay($day){

		$dayIsOk = $day >= self::MIN_DAY && $day <= self::MAX_DAY;
		if($dayIsOk){
			$this->day = $day;
		}else{
			throw new ClassHourException(self::ERR_INVALID_DAY);
		}
	}

	private function setLocal($local){

		if(is_string($local)){

			if(empty($local)){
				$local = NULL;
			}
			$this->local = $local;
		}else{
			throw new ClassHourException(self::ERR_INVALID_LOCAL);
		}
		
	}

	public function getClassHour(){

		$hour = $this->getHour();
		$day = $this->getDay();
		$local = $this->getLocal();

		$classHour = array(
			'hour' => $hour,
			'day' => $day,
			'local' => $local
		);

		return $classHour;
	}

	private function getHour(){
		return $this->hour;
	}

	private function getDay(){
		return $this->day;
	}

	private function getLocal(){
		return $this->local;
	}

	public function getDayHour(){

		$hour = $this->getHour();
		$day = $this->getDay();

		$dayHourPair = $this->convertToDayHourPair($hour, $day);

		return $dayHourPair;
	}

	private function convertToDayHourPair($hour, $day){

		try{

			$convertedHour = $this->convertHour($hour);
			$convertedDay = $this->convertDay($day);

			$dayHour = $convertedDay." ".$convertedHour;

		}catch(ClassHourException $caughException){
			
			$dayHour = "";
		}

		return $dayHour;
	}

	private function convertHour($hour){

		$convertedHour = "";

		switch($hour){
			case 1:
				$convertedHour = "06h-08h";
				break;

			case 2:
				$convertedHour = "08h-10h";
				break;

			case 3:
				$convertedHour = "10h-12h";
				break;
			
			case 4:
				$convertedHour = "12h-14h";
				break;

			case 5:
				$convertedHour = "14h-16h";
				break;

			case 6:
				$convertedHour = "16h-18h";
				break;

			case 7:
				$convertedHour = "18h-20h";
				break;

			case 8:
				$convertedHour = "20h-22h";
				break;

			case 9:
				$convertedHour = "22h-24h";
				break;

			default:
				throw new ClassHourException(self::ERR_INVALID_HOUR);
				break;
		}

		return $convertedHour;
	}

	private function convertDay($day){

		$convertedDay = "";

		switch($day){
			case 1:
				$convertedDay = "Segunda";
				break;

			case 2:
				$convertedDay = "Terça";
				break;

			case 3:
				$convertedDay = "Quarta";
				break;
			
			case 4:
				$convertedDay = "Quinta";
				break;

			case 5:
				$convertedDay = "Sexta";
				break;

			case 6:
				$convertedDay = "Sábado";
				break;

			default:
				throw new ClassHourException(self::ERR_INVALID_DAY);
				break;
		}

		return $convertedDay;
	}

}