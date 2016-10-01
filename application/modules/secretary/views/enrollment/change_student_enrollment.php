<?php

	$registration = $student['enrollment'];
				    			
	if($registration !== NULL){
		echo bold("Matrícula atual: ").$registration;
	}else{
		echo "<span class='label label-danger'> Matrícula não informada ainda.</span>";
	}

	echo form_open("secretary/enrollment/updateStudentRegistration");

		echo form_hidden(array(
			'course' => $courseId,
			'student' => $student['id']
		));

		echo "<div class='row'>";
		echo "<div class='col-md-10'>";
		echo "<div class='input-group'>";

			echo form_input(array(
				'id' => "new_registration",
				'name' => "new_registration",
				'type' => "text",
				'class' => "form-campo form-control",
				'placeholder' => "Nova matrícula",
				'maxlength' => StudentRegistration::REGISTRATION_LENGTH
			));

			echo "<span class='input-group-addon'>";
			echo form_button(array(
				'id' => "update_registration",
				'type' => "submit",
				'class' => "btn btn-success btn-flat",
				'content' => "Atualizar matrícula"
			));
			echo "</span>";

		echo "</div>";
		echo "</div>";
		echo "</div>";

	echo form_close();