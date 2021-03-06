<?php

function getCurrentYear(){

	$ci = get_instance();

	$getYearSql = "SELECT YEAR(CURDATE())";
	$currentYear = $ci->db->query($getYearSql)->row_array();

	$currentYear = checkArray($currentYear);

	if($currentYear !== FALSE){
		$currentYear = $currentYear['YEAR(CURDATE())'];
	}else{
		$currentYear = FALSE;
	}

	return $currentYear;
}

function convertDateToDB($date, $format='d/m/Y'){
	$dateTimeReady = convertDateToDateTime($date, $format);
	try{
		$dateTimeObj = new DateTime($dateTimeReady);
		$dateDbReady = $dateTimeObj->format("Y/m/d");
	    return $dateDbReady;
	}catch(Exception $e){
		throw new RuntimeException("The '{$date}' is invalid.");
	}
}

function convertDateToDateTime($date, $format='d/m/Y'){

	$validDate = validateDate($date, $format);

	if($validDate){
		$date = formatDateToDateTime($validDate);
	}
	else{
		$date = NULL;
	}

	return $date;
}

function validateDate($date, $format="d/m/Y"){

	$date = date_parse_from_format($format, $date);
	$dateIsValid = $date["year"] !== FALSE && $date["month"] !== FALSE
				   && $date["day"] !== FALSE && $date["error_count"] === 0
				   && $date["warning_count"] === 0;

	if(!$dateIsValid){
		$date = FALSE;
	}

	return $date;
}

function formatDateToDateTime($date){

	$day = $date["day"];
	$month = $date["month"];
	$year = $date["year"];

	$strDay = (string) $day;
	$strMonth = (string) $month;

	if(strlen($strDay) === 1){
		$day = "0".$day;
	}

	if(strlen($strMonth) === 1){
		$month = "0".$month;
	}

	// Valid format date to DateTime class
	$formattedDate = $year."/".$month."/".$day;

	return $formattedDate;
}

function validateDatesDiff($startDate, $endDate){

	// The end date must be later than or equal the start date
	if($endDate < $startDate){
		$validDate = FALSE;
	}
	else{
		$validDate = TRUE;
	}

	return $validDate;
}

function convertDateTimeToDateBR($date){
	if($date != NULL){
		$date = new DateTime($date);
		$date = $date->format("d/m/Y");
	}

	return $date;
}

function validateDateInPeriod($date, DateTime $startDate=null, DateTime $endDate=null){

	$validDate = FALSE;
	if(!is_null($startDate) && !is_null($endDate)){
		$intervalStartDate = $startDate->diff($date);
		$intervalEndDate = $endDate->diff($date);
		$validStartDate = ($intervalStartDate->invert == 0 && $intervalStartDate->days >= 0) || ($intervalStartDate->invert == 1 && $intervalStartDate->days == 0);
		$validEndDate = ($intervalEndDate->invert == 1 && $intervalEndDate->days > 0) || ($intervalEndDate->invert == 0 && $intervalEndDate->days == 0);
		if($validStartDate && $validEndDate){
			$validDate = TRUE;
		}
	}
	return $validDate;
}

function getYearsOfAPeriod($startYear, $endYear){

	$years = array((int) $startYear);
	for ($year=($startYear + 1); $year <= $endYear; $year++) {
		$years[] = (int) $year;
	}

	return $years;
}

/**
* @param $dateToValidate in datetime
*/
function validateIfDateIsFutureDate($dateToValidate){

	$today = new DateTime("America/Sao_Paulo");
	$today->setTime("0","0","0");

	$validDate = validateDatesDiff($today, $dateToValidate);

	return $validDate;
}