<?php 

require_once(APPPATH."/controllers/Semester.php");

function printCurrentSemester(){

	$semester = new Semester();
	$currentSemester = $semester->getCurrentSemester();
	$currentSemester = $currentSemester['description'];

	echo "<h3><span class='label label-primary'> Semestre atual: {$currentSemester}</span></h3>";
}