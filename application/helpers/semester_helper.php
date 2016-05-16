<?php 

require_once(APPPATH."/controllers/Semester.php");

function printCurrentSemester(){

	$ci =& get_instance();
   	$ci->load->model("program/semester_model");
   	
   	$currentSemester = $this->semester_model->getCurrentSemester();
	$currentSemester = $currentSemester['description'];

	echo "<h3><span class='label label-primary'> Semestre atual: {$currentSemester}</span></h3>";
}