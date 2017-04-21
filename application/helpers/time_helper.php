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

function convertDateToDateTime($date){

	$validDate = validateDate($date);

	if($validDate){
		$date = formatDateToDateTime($validDate);
	}
	else{
		$date = NULL;
	}

	return $date;
}

function validateDate($date){

	$date = date_parse_from_format("d/m/Y", $date);
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

	// The end date must be later than the start date
	if($endDate <= $startDate){
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


// params should be in datetime (not in string)
function validateDateInPeriod($date, $startDate, $endDate){

	$intervalStartDate = $startDate->diff($date);
	$intervalEndDate = $endDate->diff($date);
	$validStartDate = ($intervalStartDate->invert == 0 && $intervalStartDate->days >= 0) || ($intervalStartDate->invert == 1 && $intervalStartDate->days == 0);
	$validEndDate = ($intervalEndDate->invert == 1 && $intervalEndDate->days > 0) || ($intervalEndDate->invert == 0 && $intervalEndDate->days == 0);
	if($validStartDate && $validEndDate){
		$validDate = TRUE;
	}
	else{
		$validDate = FALSE;
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