<?php 

function printCurrentSemester(){

	$ci =& get_instance();
   	$ci->load->model("program/semester_model");
   	
   	$currentSemester = $ci->semester_model->getCurrentSemester();
	$currentSemester = $currentSemester['description'];

	echo "<h3><span class='label label-primary'> Semestre atual: {$currentSemester}</span></h3>";
}