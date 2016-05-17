<?php

	$submitBtn = array(
		"class" => "btn bg-olive btn-block",
		"content" => "Relacionar",
		"type" => "submit"
	);

	if($students === FALSE){
		$thereIsNoStudents = TRUE;
		$students = array("Nenhum aluno neste curso.");
		$submitBtn['disabled'] = TRUE;
	}else{
		$thereIsNoStudents = FALSE;
	}

	if($masterminds === FALSE){
		$thereIsNoMasterminds = TRUE;
		$masterminds = array("Nenhum orientador cadastrado.");
		$submitBtn['disabled'] = TRUE;
	}else{
		$thereIsNoMasterminds = FALSE;
	}

	echo "<div class='form-box' id='login-box'>";
		echo "<div class='header'>Relacionar Orientador a Aluno</div>";

		echo form_open('program/mastermind/saveMastermindToStudent','',array('courseId'=>$courseId));
		echo "<div class='body bg-gray'>";
			echo "<div class='form-group'>";
				echo form_label("Aluno do curso", "course_student") . "<br>";
				echo form_dropdown("course_student", $students, '', "id='course_student'");
				echo form_error("course_student");
				echo "<br>";
				echo "<br>";
			echo "</div>";
			echo "<div class='form-group'>";
				echo form_label("Orientador para este aluno", "student_mastermind") . "<br>";
				echo form_dropdown("student_mastermind", $masterminds, '', "id='student_mastermind'");
				echo form_error("student_mastermind");
				echo "<br>";
				echo "<br>";
			echo "</div>";
		echo "</div>";
		echo "<div class='footer body bg-gray'>";
			echo form_button($submitBtn);
		echo "</div>";

		if($thereIsNoStudents){
			echo "<div class='callout callout-danger'>";
				echo "<h4>Não é possível relacionar orientadores sem alunos.</h4>";
			echo "</div>";
		}
		if($thereIsNoMasterminds){
			echo "<div class='callout callout-danger'>";
				echo "<h4>Não é possível relacionar orientadores sem orientadores.</h4>";
			echo "</div>";
		}

		echo form_close();
	echo "</div>";