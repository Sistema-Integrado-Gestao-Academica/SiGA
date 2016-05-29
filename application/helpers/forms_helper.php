
<?php
require_once(APPPATH."/constants/TeacherConstants.php");

function bold($string){
	return "<b>".$string."</b>";
}

function provideDocOnlineForm($request, $course){

	echo form_open_multipart("provide_doc_online");

	$hidden = array(
		'course' => $course,
		'request' => $request
	);

	echo form_hidden($hidden);

	$file = array(
		"name" => "requested_doc",
		"id" => "requested_doc",
		"type" => "file"
	);
	
	$submitFileBtn = array(
		"id" => "provide_online_btn",
		"class" => "btn btn-info btn-flat",
		"content" => "<i class='fa fa-globe'></i> Expedir online",
		"type" => "submit"
		// "style" => "margin-top: 5%;"
	);

	echo form_label("Selecionar documento <small><i>('.pdf', '.png' e '.jpg' apenas)</i></small>:", "requested_doc");

	echo form_input($file);
	echo "<br>";

	echo form_button($submitFileBtn);

	echo form_close();
}

function searchForDisciplineByNameForm($syllabusId, $courseId){

	$discipline = array(
		"name" => "discipline_tomo_search",
		"id" => "discipline_to_search",
		"type" => "text",
		"class" => "form-campo form-control",
		"placeholder" => "Informe o nome da disciplina...",
		"maxlength" => "50",
		'style' => "width:80%;"
	);

	$searchForDisciplineBtn = array(
		"id" => "search_student_request_btn",
		"class" => "btn bg-primary btn-flat",
		"content" => "Pesquisar por nome da disciplina",
		"type" => "submit"
	);

	define("SEARCH_BY_NAME", "by_name");

	echo "<h4><i class='fa fa-search'></i> Pesquisar por nome da disciplina</h4>";
	echo form_open("secretary/syllabus/searchForDiscipline");
		echo form_hidden('searchType', SEARCH_BY_NAME);
		echo form_hidden('syllabusId', $syllabusId);
		echo form_hidden('courseId', $courseId);

		echo "<div class='form-group'>";
			echo form_label("Informe o nome da disciplina para pesquisar:", "discipline_to_search");
			echo form_input($discipline);
		echo "</div>";

		echo form_button($searchForDisciplineBtn);
	echo form_close();
}

function searchForDisciplineByIdForm($syllabusId, $courseId){

	$discipline = array(
		"name" => "discipline_to_search",
		"id" => "discipline_to_search",
		"type" => "text",
		"class" => "form-campo form-control",
		"placeholder" => "Informe o código da disciplina...",
		"maxlength" => "10",
		'style' => "width:80%;"
	);

	$searchForDisciplineBtn = array(
		"id" => "search_student_request_btn",
		"class" => "btn bg-primary btn-flat",
		"content" => "Pesquisar por código da disciplina",
		"type" => "submit"
	);

	define("SEARCH_BY_ID", "by_id");

	echo "<h4><i class='fa fa-search'></i> Pesquisar por código da disciplina</h4>";
	echo form_open("secretary/syllabus/searchForDiscipline");
		echo form_hidden('searchType', SEARCH_BY_ID);
		echo form_hidden('syllabusId', $syllabusId);
		echo form_hidden('courseId', $courseId);

		echo "<div class='form-group'>";
			echo form_label("Informe o código da disciplina para pesquisar:", "discipline_to_search");
			echo form_input($discipline);
		echo "</div>";

		echo form_button($searchForDisciplineBtn);
	echo form_close();
}

function searchForStudentRequestByIdForm($courseId){

	$student = array(
		"name" => "student_identifier",
		"id" => "student_identifier",
		"type" => "text",
		"class" => "form-campo",
		"class" => "form-control",
		"placeholder" => "Informe a matrícula do aluno...",
		"maxlength" => "50",
		'style' => "width:80%;"
	);

	$searchForStudentBtn = array(
		"id" => "search_student_request_btn",
		"class" => "btn bg-primary btn-flat",
		"content" => "Pesquisar por matrícula",
		"type" => "submit"
	);

	define("SEARCH_BY_ENROLLMENT", "by_enrollment");

	echo "<h4><i class='fa fa-search'></i> Pesquisar por matrícula do aluno</h4>";
	echo form_open("secretary/request/searchForStudentRequest");
		echo form_hidden('searchType', SEARCH_BY_ENROLLMENT);
		echo form_hidden('courseId', $courseId);

		echo "<div class='form-group'>";
			echo form_label("Informe a matrícula do aluno para pesquisar:", "student_identifier");
			echo form_input($student);
		echo "</div>";

		echo form_button($searchForStudentBtn);
	echo form_close();
}

function searchForStudentRequestByNameForm($courseId){

	$student = array(
		"name" => "student_identifier",
		"id" => "student_identifier",
		"type" => "text",
		"class" => "form-campo",
		"class" => "form-control",
		"placeholder" => "Informe o nome do aluno...",
		"maxlength" => "50",
		'style' => "width:80%;"
	);

	$searchForStudentBtn = array(
		"id" => "search_student_request_btn",
		"class" => "btn bg-primary btn-flat",
		"content" => "Pesquisar por nome",
		"type" => "submit"
	);

	define("SEARCH_BY_NAME", "by_name");

	echo "<h4><i class='fa fa-search'></i> Pesquisar por nome do aluno</h4>";
	echo form_open("secretary/request/searchForStudentRequest");
		echo form_hidden('searchType', SEARCH_BY_NAME);
		echo form_hidden('courseId', $courseId);

		echo "<div class='form-group'>";
			echo form_label("Informe o nome do aluno para pesquisar:", "student_identifier");
			echo form_input($student);
		echo "</div>";

		echo form_button($searchForStudentBtn);
	echo form_close();
}

function mastermindMessageForm($requestId, $mastermindId, $isFinalized, $mastermindMessage = ""){

	$hidden = array(
		'requestId' => $requestId,
		'mastermindId' => $mastermindId
	);

	$message = array(
		'name' => 'mastermind_message',
		'id' => 'mastermind_message',
		'placeholder' => 'Deixe aqui sua mensagem para o aluno.',
		'rows' => '20',
		"class" => "form-control",
		'style' => 'height: 70px; margin-top:-10%;'
	);

	if($isFinalized){

		$message['value'] = $mastermindMessage;

		$submitBtn = array(
			"class" => "btn btn-warning btn-flat",
			"content" => "Alterar mensagem",
			"type" => "submit"
		);

	}else{
		$submitBtn = array(
			"class" => "btn btn-primary btn-flat",
			"content" => "Finalizar solicitação",
			"type" => "submit"
		);
	}

	echo form_open('program/mastermind/finalizeRequest','',$hidden);
	echo form_label('Mensagem:', 'mastermind_message');
	echo "<br>";
	echo "<br>";
	echo form_textarea($message);
	echo form_button($submitBtn);
	echo form_close();
}

function studentBasicInfoForm($path, $hiddenData, $previousData = FALSE){

	$cellPhone = array(
		"name" => "cell_phone_number",
		"id" => "cell_phone_number",
		"type" => "number",
		"class" => "form-campo",
		"class" => "form-control",
		"maxlength" => "11"
	);

	$homePhone = array(
		"name" => "home_phone_number",
		"id" => "home_phone_number",
		"type" => "number",
		"class" => "form-campo",
		"class" => "form-control",
		"maxlength" => "11"
	);

	$submitBtn = array(
		"class" => "btn btn-success btn-block ",
		"content" => "Atualizar dados",
		"type" => "submit"
	);

	if($previousData !== FALSE){
		$cellPhone['value'] = $previousData['cell_phone'];
		$homePhone['value'] = $previousData['home_phone'];
	}

	echo form_open($path,'',$hiddenData);
	echo "<div class='body bg-gray'>";
		echo "<div class='form-group'>";
			echo form_label("Telefone Residencial", "home_phone_number") . "<br>";
			echo form_input($homePhone);
			echo form_error("home_phone_number");
		echo "</div>";
		
		echo "<div class='form-group'>";
			echo form_label("Telefone Celular", "cell_phone_number") . "<br>";
			echo form_input($cellPhone);
			echo form_error("cell_phone_number");
		echo "</div>";
	echo "</div>";
	echo "<div class='footer'>";
		echo form_button($submitBtn);
	echo "</div>";
	echo form_close();
}

function formToEnrollTeacherToCourse($courseTeachers, $teachers, $courseId){

	$submitBtn = array(
		"class" => "btn bg-olive btn-block",
		"type" => "submit",
		"content" => "Vincular docente"
	);

	if($teachers !== FALSE){

		$thereIsTeachers = TRUE;

		// Just a copy of the array '$teachers'
		$t = $teachers;

		if($courseTeachers !== FALSE){

			foreach($t as $userId => $teacher){
				foreach($courseTeachers as $courseTeacher){
					if($courseTeacher['id_user'] == $userId){

						unset($teachers[$userId]);

						if(sizeof($teachers) === 0){
							$submitBtn['disabled'] = TRUE;
							$teachers = array("Docentes do sistema já vinculados ao curso");
						}
					}
				}
			}
		}

	}else{
		$thereIsTeachers = FALSE;
		$submitBtn['disabled'] = TRUE;
		$teachers = array('Nenhum docente cadastrado.');
	}

	echo form_open("secretary/enrollTeacherToCourse");
		echo form_hidden('courseId', $courseId);
		echo "<div class='form-box'>";
			echo"<div class='header'>Vincular docente ao curso</div>";
			echo "<div class='body bg-gray'>";

				echo "<div class='form-group'>";
					echo form_label("Docente:", "teacher");
						echo form_dropdown("teacher", $teachers, '', "class='form-control'");
					echo form_error("teacher");
				echo "</div>";

			echo "</div>";
			echo "<div class='footer bg-gray'>";
				echo form_button($submitBtn);
			echo "</div>";

			if(!$thereIsTeachers){
				echo "<div class='callout callout-danger'>";
					echo "<h4>Não há docentes cadastrados no sistema.</h4>";
				echo "</div>";
			}
		echo "</div>";

	echo form_close();
}

function formToDefineTeacherSituation($teacherId, $courseId, $oldSituation){

	$submitBtn = array(
		"class" => "btn btn-primary btn-flat",
		"type" => "submit",
		"content" => "Definir situação"
	);

	$teacherConstants = new TeacherConstants();
	$situations = $teacherConstants->getSituations();

	echo form_open("secretary/defineTeacherSituation");
		echo form_hidden("teacherId", $teacherId);
		echo form_hidden("courseId", $courseId);

		if($oldSituation !== FALSE){
			echo form_label("Atualizar situação do docente:", "situation");
			echo form_dropdown("situation", $situations, $oldSituation, "class='form-control'");
		}else{
			echo form_label("Situação do docente:", "situation");
			echo form_dropdown("situation", $situations, '', "class='form-control'");
		}
		echo form_error("situation");

		echo form_button($submitBtn);
	echo form_close();
}

function formToNewOfferDisciplineClass($idDiscipline, $idOffer, $teachers, $idCourse){

	$disciplineClass = array(
		"name" => "disciplineClass",
		"id" => "disciplineClass",
		"type" => "text",
		"class" => "form-campo",
		"class" => "form-control",
		"maxlength" => "3"
	);

	$totalVacancies = array(
		"name" => "totalVacancies",
		"id" => "totalVacancies",
		"type" => "number",
		"class" => "form-campo",
		"class" => "form-control",
		"min" => "0",
		"value" => "0"
	);

	$submitBtn = array(
		"class" => "btn bg-olive btn-block",
		"type" => "submit",
		"content" => "Cadastrar turma"
	);

	echo form_open("secretary/offer/newOfferDisciplineClass/{$idDiscipline}/{$idOffer}/{$idCourse}");

		echo "<div class='form-box'>";
			echo"<div class='header'>Nova turma para oferta</div>";
			echo "<div class='body bg-gray'>";

				echo "<div class='form-group'>";
					echo form_label("Turma", "disciplineClass");
					echo form_input($disciplineClass);
					echo form_error("disciplineClass");
				echo "</div>";

				echo "<div class='form-group'>";
					echo form_label("Vagas totais", "totalVacancies");
					echo form_input($totalVacancies);
					echo form_error("disciplineClass");
				echo "</div>";

				echo "<div class='form-group'>";
					echo form_label("Professor principal", "mainTeacher");
					if($teachers !== FALSE){
						echo form_dropdown("mainTeacher", $teachers, '', "class='form-control'");
					}else{
						$submitBtn['disabled'] = TRUE;
						echo form_dropdown("mainTeacher", array('Nenhum professor cadastrado.'), '', "class='form-control'");
					}
					echo form_error("mainTeacher");
				echo "</div>";

				echo "<div class='form-group'>";
					echo form_label("Professor secundário", "secondaryTeacher");
					if($teachers !== FALSE){
						define("NONE_TEACHER", 0);
						$teachers[NONE_TEACHER] = "Nenhum";
						echo form_dropdown("secondaryTeacher", $teachers, NONE_TEACHER, "class='form-control'");
					}else{
						echo form_dropdown("secondaryTeacher", array('Nenhum professor cadastrado.'), '', "class='form-control'");
					}
					echo form_error("secondaryTeacher");
				echo "</div>";

			echo "</div>";
			echo "<div class='footer bg-gray'>";
				echo form_button($submitBtn);
			echo "</div>";
		echo "</div>";

	echo form_close();

	if($teachers === FALSE){

		echo "<div class='callout callout-danger'>";
			echo "<h4>Não é possível cadastrar uma turma para oferta sem um professor principal.</h4>";
			echo "<p>Contate o administrador.</p>";
		echo "</div>";
	}
}

function createResearchLineForm($courses){

	$submitBtn = array(
			"class" => "btn bg-olive btn-block",
			"content" => "Salvar",
			"type" => "submit"
	);

	if($courses === FALSE){
		$courses = array("Não há cursos para este secretário.");
		$submitBtn['disabled'] = TRUE;
	}

	$researchLine = array(
			"name" => "researchLine",
			"id" => "researchLine",
			"type" => "text",
			"class" => "form-campo",
			"class" => "form-control",
			"maxlength" => "80"
	);


	echo "<div class='form-box' id='login-box'>";
		echo "<div class='header'>Cadastrar nova Linha de Pesquisa</div>";

		echo form_open('program/course/saveResearchLine','');
		echo "<div class='body bg-gray'>";
			echo "<div class='form-group'>";
			echo form_label("Linha de Pesquisa", "research_line");
				echo form_input($researchLine);
			echo form_error("research_line");
		echo "</div>";
		echo "<div class='form-group'>";
			echo form_label("Curso da Linha de Pesquisa", "research_course");
			echo form_dropdown("research_course", $courses, '', "id='research_course'");
			echo form_error("research_course");
		echo "</div>";
	echo "</div>";

	echo "<div class='footer body bg-gray'>";
		echo form_button($submitBtn);
	echo "</div>";

	echo form_close();
	echo "</div>";
}

function updateResearchLineForm($researchId, $description, $actualCourseForm, $courses){


	$submitBtn = array(
			"class" => "btn bg-olive btn-block",
			"content" => "Salvar",
			"type" => "submit"
	);

	$researchLine = array(
			"name" => "researchLine",
			"id" => "researchLine",
			"type" => "text",
			"class" => "form-campo",
			"class" => "form-control",
			"maxlength" => "80",
			"value" => $description
	);

	$hidden = array('id_research_line'=>$researchId);

	echo "<div class='form-box' id='login-box'>";
		echo "<div class='header'>Cadastrar nova Linha de Pesquisa</div>";

		echo form_open('program/course/updateResearchLine','',$hidden);
		echo "<div class='body bg-gray'>";
			echo "<div class='form-group'>";
				echo form_label("Linha de Pesquisa", "research_line");
				echo form_input($researchLine);
				echo form_error("research_line");
			echo "</div>";
			echo "<div class='form-group'>";
				echo form_label("Curso da Linha de Pesquisa", "research_course");
				echo form_dropdown("research_course", $courses, $actualCourseForm, "id='research_course'");
				echo form_error("research_course");
		echo "</div>";
	echo "</div>";

	echo "<div class='footer body bg-gray'>";
	echo form_button($submitBtn);
	echo "</div>";

	echo form_close();
	echo "</div>";
}

function relateDisciplineToResearchLineForm($researchLines, $discipline, $syllabusId, $courseId){

	$submitBtn = array(
			"class" => "btn bg-olive btn-block",
			"content" => "Salvar",
			"type" => "submit"
	);

	$thereIsNoResearchLines = sizeof($researchLines) == 1;
	if($thereIsNoResearchLines){
		$submitBtn['disabled'] = TRUE;
	}

	$hidden = array(
			'discipline_code'=>$discipline['discipline_code'],
			'syllabusId' => $syllabusId,
			'courseId' => $courseId
	);

			echo "<div class='form-box' id='login-box'>";
				echo "<div class='header'>Cadastrar nova Linha de Pesquisa</div>";

				echo form_open('secretary/syllabus/saveDisciplineResearchLine','',$hidden);
				echo "<div class='body bg-gray'>";
					echo "<div class='form-group'>";
						echo $discipline['discipline_code']." - ".$discipline['discipline_name']." (".$discipline['name_abbreviation'].")";
					echo "</div>";
					echo "<div class='form-group'>";
						echo form_label("Linha de Pesquisa da Disciplina", "research_line");
						echo form_dropdown("research_line", $researchLines, "id='research_line'");
						echo form_error("research_line");
					echo "</div>";
			echo "</div>";

			echo "<div class='footer body bg-gray'>";
				echo form_button($submitBtn);
			echo "</div>";

			echo form_close();

}

function loadStaffRegistrationForm($users){

	/**
	 *	New staff Labels
	 */
	$submitBtn = array(
			"class" => "btn bg-olive btn-block",
			"content" => "Salvar",
			"type" => "submit"
	);

	$pisNumber = array(
			"name" => "pis",
			"id" => "pis",
			"type" => "text",
			"class" => "form-campo",
			"class" => "form-control",
			"maxlength" => "11"
	);

	$registration = array(
			"name" => "registration",
			"id" => "registration",
			"type" => "text",
			"class" => "form-campo",
			"class" => "form-control",
			"maxlength" => "10",
			"placeholder" => "Opcional",
			"value" => NULL
	);

	$landingDate = array(
			"name" => "arrivalInBrazil",
			"id" => "arrivalInBrazil",
			"type" => "text",
			"class" => "form-campo",
			"class" => "form-control",
			"placeholder" => "Opcional",
			"value" => NULL
	);

	$address = array(
			"name" => "address",
			"id" => "address",
			"type" => "text",
			"class" => "form-campo",
			"class" => "form-control",
			"maxlength" => "50"
	);

	$phone = array(
			"name" => "telephone",
			"id" => "telephone",
			"type" => "text",
			"class" => "form-campo",
			"class" => "form-control",
			"maxlength" => "15"
	);

	/**
	 *	Bank labels
	 */

	$bank = array(
			"name" => "bank",
			"id" => "bank",
			"type" => "text",
			"class" => "form-campo",
			"class" => "form-control",
			"maxlength" => "25",
			"value" => NULL
	);

	$agency = array(
			"name" => "agency",
			"id" => "agency",
			"type" => "text",
			"class" => "form-campo",
			"class" => "form-control",
			"maxlength" => "10",
			"value" => NULL
	);

	$checkingAccount = array(
			"name" => "accountNumber",
			"id" => "accountNumber",
			"type" => "text",
			"class" => "form-campo",
			"class" => "form-control",
			"maxlength" => "15",
			"value" => NULL
	);

	if($users == FALSE){
		$submitBtn['disabled'] = TRUE;
		$pisNumber['disabled'] = TRUE;
		$registration['disabled'] = TRUE;
		$landingDate['disabled'] = TRUE;
		$address['disabled'] = TRUE;
		$phone['disabled'] = TRUE;
		$bank['disabled'] = TRUE;
		$agency['disabled'] = TRUE;
		$checkingAccount['disabled'] = TRUE;
	}

	echo "<div class='form-box' id='login-box'>";
		echo "<div class='header'>Cadastrar novo Funcionário</div>";

		echo form_open('program/staff/newStaff','');
		echo "<div class='body bg-gray'>";
			echo "<div class='form-group'>";
				echo form_label("PIS/INSS", "pis");
				echo form_input($pisNumber);
				echo form_error("pis");
			echo "</div>";
			echo "<div class='form-group'>";
			echo form_label("Selecione o Funcionário", "staff");
			if($users !== FALSE){

				echo form_dropdown("staff", $users, "id='staff'");
			}
			else{
				echo form_dropdown("staff", array("Não há usuários para cadastrar como funcionário"), "id='staff'");
			}
			echo form_error("staff");
			echo "</div>";
			echo "<div class='form-group'>";
				echo form_label("Matrícula", "registration");
				echo form_input($registration);
				echo form_error("registration");
			echo "</div>";
			echo "<div class='form-group'>";
				echo form_label("Chegada ao Brasil", "arrivalInBrazil");
				echo form_input($landingDate);
				echo form_error("arrivalInBrazil");
			echo "</div>";
			echo "<div class='form-group'>";
				echo form_label("Endereço", "address");
				echo form_input($address);
				echo form_error("address");
			echo "</div>";
			echo "<div class='form-group'>";
				echo form_label("Telefone", "telephone");
				echo form_input($phone);
				echo form_error("telephone");
			echo "</div>";

			echo "<hr>";
			echo "<h3>Dados Bancários</h3> (Opcionais)";

			echo "<div class='form-group'>";
				echo form_label("Banco", "bank");
				echo form_input($bank);
				echo form_error("bank");
			echo "</div>";
			echo "<div class='form-group'>";
				echo form_label("Agência", "agency");
				echo form_input($agency);
				echo form_error("agency");
			echo "</div>";
			echo "<div class='form-group'>";
				echo form_label("Conta Corrente", "accountNumber");
				echo form_input($checkingAccount);
				echo form_error("accountNumber");
			echo "</div>";

		echo "</div>";

		echo "<div class='footer body bg-gray'>";
		echo form_button($submitBtn);
		echo "</div>";

		echo form_close();
		if($users == FALSE){
			callout("danger", "Não há usuários para cadastrar como funcionário.", "Apenas usuários do grupo convidado podem ser cadastrados com funcionários.");
		}
	echo "</div>";

}

function emptyDiv(){
	echo "";
}