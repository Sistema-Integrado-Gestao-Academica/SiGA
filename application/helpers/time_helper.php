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

	$date = new DateTime($date);
	$date = $date->format("d/m/Y");
	
	return $date;
}
