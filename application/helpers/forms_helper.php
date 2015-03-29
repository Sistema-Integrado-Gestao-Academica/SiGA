
<?php

function addDisciplinesToRequestForm($courseId, $userId, $semesterId){
	
	$disciplineCode = array(
		"name" => "discipline_code_search",
		"id" => "discipline_code_search",
		"type" => "text",
		"class" => "form-campo",
		"class" => "form-control",
		"maxlength" => "8"
	);

	$disciplineClass = array(
		"name" => "discipline_class_search",
		"id" => "discipline_class_search",
		"type" => "text",
		"class" => "form-campo",
		"class" => "form-control",
		"maxlength" => "3"
	);

	$searchBtn = array(
		"id" => "discipline_search_btn",
		"class" => "btn bg-blue btn-block",
		"content" => "Adicionar disciplina",
		"type" => "submit",
		"style" => "width:80%"
	);

	$hidden = array(
		'courseId' => $courseId,
		'userId' => $userId,
		'semesterId' => $semesterId
	);

	echo "<h3><i class='fa fa-search-plus'> </i> Adicionar disciplinas</h3>";
	echo"<br>";

	echo form_open('temporaryrequest/addTempDisciplinesToRequest', array('role' => "form"), $hidden);
		echo "<div class='row'>";
			echo "<div class='col-lg-3'>";
				echo "<div class='input-group input-group-sm'>";
				echo form_label("Código da disciplina", "discipline_code_search");
				echo form_input($disciplineCode);
				echo "</div>";
			echo "</div>";
			echo "<div class='col-lg-2'>";
				echo "<div class='input-group input-group-sm'>";
				echo form_label("Turma", "discipline_name_search");
				echo form_input($disciplineClass);
				echo "</div>";
			echo "</div>";
		echo "</div>";
	echo "<br>";
		echo "<div class='row'>";
			echo "<div class='col-lg-3'>";
				echo form_button($searchBtn);
			echo "</div>";
		echo "</div>";
	echo form_close();
}

function displayEnrollStudentForm(){
	
	$studentName = array(
		"name" => "student_name",
		"id" => "student_name",
		"type" => "text",
		"class" => "form-campo",
		"class" => "form-control",
		"maxlength" => "50",
		'style' => "width:40%;"
	);

	$searchForStudentBtn = array(
		"id" => "search_student_btn",
		"class" => "btn bg-olive btn-block",
		"content" => "Procurar por aluno",
		"type" => "submit",
		'style' => "width:15%;"
	);

	echo form_label("Informe o nome do usuário para matricular nesse curso:");
	echo form_input($studentName);

	echo form_button($searchForStudentBtn);
}

function displayEnrollMastermindToStudentForm($students, $masterminds, $courseId){
	$submit_button_array_to_form = array(
		"class" => "btn bg-olive btn-block",
		"content" => "Relacionar",
		"type" => "submit"
	);
	
	echo "<div class='form-box' id='login-box'>";
		echo "<div class='header'>Relacionar Orientador a Aluno</div>";
		
		echo form_open('mastermind/saveMastermindToStudent','',array('courseId'=>$courseId));
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
		echo "<div class='footer'>";
			echo form_button($submit_button_array_to_form);
		echo "</div>";
		echo form_close();
	echo "</div>";
	
}

function emptyDiv(){
	echo "";
}