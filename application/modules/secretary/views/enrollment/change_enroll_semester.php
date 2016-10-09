<?php

	$semesterId = $student['enroll_semester'];
	$this->load->model("program/semester_model");
				    			
	if($semesterId !== NULL){
		$semester = $this->semester_model->getSemesterById($semesterId);
		echo bold("Semestre de entrada: ").$semester['description'];
	}
	else{
		echo "<span class='label label-warning'> Semestre de entrada não informado ainda.</span>";
	}
	echo "<br>";
	$semestersList = $this->semester_model->getPossibleSemesters();
	$studentId = $student['id'];
	echo form_open("student/student/updateStudentSemester/{$studentId}");

		echo form_hidden(array(
			'course' => $courseId
		));

		echo "<div class='row'>";
		echo "<div class='col-md-5'>";
		echo "<div class='input-group'>";

			echo form_dropdown("new_semester", $semestersList, '', "class='form-campo form-control'");

			echo "<span class='input-group-addon'>";
			echo form_button(array(
				'id' => "update_semester",
				'type' => "submit",
				'class' => "btn btn-success btn-flat",
				'content' => "Atualizar semestre de entrada"
			));
			echo "</span>";

		echo "</div>";
		echo "</div>";
		echo "</div>";

	echo form_close();