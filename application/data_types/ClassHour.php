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
			throw new ClassHourException("Hour out of range 1-9");
		}
	}

	private function setDay($day){

		$dayIsOk = $day >= self::MIN_DAY && $day <= self::MAX_DAY;
		if($dayIsOk){
			$this->day = $day;
		}else{
			throw new ClassHourException("Day out of range 1-6");
		}
	}

	private function setLocal($local){

		if(is_string($local)){

			if(empty($local)){
				$local = NULL;
			}
			$this->local = $local;
		}else{
			throw new ClassHourException("Local of class must be a string");
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

}