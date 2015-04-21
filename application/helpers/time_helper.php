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