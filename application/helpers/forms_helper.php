<?php

function postGraduationTypesSelect(){
	$post_graduation_types = array(
		'academic_program' => 'Programa Acadêmico',
		'professional_program' => 'Programa Profissional'
	);
	$courseDuration = "<br>Duração mínima - 18 meses<br>Regular - 24 meses<br>Máxima - 30 meses<br>";

	echo form_label('Escolha o tipo da Pós-graduação');
	echo form_dropdown('post_graduation_type', $post_graduation_types,
				       'academic_program', 'id=post_graduation_type');
	echo $courseDuration;
}

function academicProgramForm(){

	echo "<h3><span class='label label-primary'>Programa Acadêmico</span></h3>";
	
	echo "<br>";
	echo "<h4><span class='label label-default'>Mestrado Acadêmico</span></h4>";
	echo "<small> Para cadastrar um Doutorado acesse a página para editar um curso.</small>";
	
	commonAttrForPostGraduationCourses();
}

function professionalProgramForm(){
	
	echo "<h3><span class='label label-primary'>Programa Profissional</span></h3>";
	
	echo "<br>";
	echo "<h4><span class='label label-default'>Mestrado Profissional</span></h4>";

	commonAttrForPostGraduationCourses();
}

function masterDegreeProgramForm(){

	echo "<h4><span class='label label-default'>Mestrado Acadêmico - Alterar</span></h4>";

	commonAttrForPostGraduationCourses();
}

function doctorateProgramForm(){

	echo "<h4><span class='label label-default'>Doutorado Acadêmico</span></h4>";

	commonAttrForPostGraduationCourses();
}

function formToCreateDoctorateCourse(){

	$doctorate_course_name = array(
		"name" => "doctorate_course_name",
		"id" => "doctorate_course_name",
		"type" => "text",
		"class" => "form-campo",
		"maxlength" => "50",
		"style" => "width: 80%;"
	);

	$submit_button = array(
		"class" => "btn btn-primary",
		"content" => "Cadastrar Doutorado",
		"type" => "submit"
	);

	echo "<h4><span class='label label-default'>Doutorado Acadêmico</span></h4>";

	echo "<br>";
	echo form_label('Nome do curso', 'doctorate_course_name');
	echo form_input($doctorate_course_name);
	
	commonAttrForPostGraduationCourses();
	
	echo "<br><br>";
	echo form_button($submit_button);
}

function commonAttrForPostGraduationCourses(){
	
	$course_duration = array(
		'2' => '2 anos',
		'4' => '4 anos'
	);

	$total_credits = array(
		'name' => 'course_total_credits',
		'id' => 'course_total_credits',
		'maxlength' => '10',
		'style' => 'width: 40%;',
	);

	$course_hours = array(
		'name' => 'course_hours',
		'id' => 'course_hours',
		'maxlength' => '10',
		'style' => 'width: 40%;',
	);

	$course_class = array(
		'name' => 'course_class',
		'id' => 'course_class',
		'placeholder' => 'Informe o semestre de início.',
		'maxlength' => '6',
		'style' => 'width: 40%;',
	);

	$description = array(
		'name' => 'course_description',
		'id' => 'course_description',
		'placeholder' => 'Informe a descrição do curso.',
		'rows' => '500',
		'style' => 'width: 85%; height: 100px;',
	);

	// Course duration field
	echo "<br><br>";
	echo form_label('Duração do curso ', 'course_duration');
	echo form_dropdown('course_duration', $course_duration, '2', 'id=course_duration');
	echo "<br><br>";

	// Course total credits field
	echo form_label('Créditos totais', 'total_credits');
	echo "<br>";
	echo form_input($total_credits);
	echo "<br>";

	// Course hours field
	echo "<br>";
	echo form_label('Carga-horária total', 'course_hours');
	echo "<br>";
	echo form_input($course_hours);
	echo "<br>";

	// Course class field
	echo "<br>";
	echo form_label('Turma', 'course_class');
	echo form_input($course_class);
	echo "<br>";

	// Course description field
	echo "<br>";
	echo form_label('Descrição ', 'course_description');
	echo "<br>";
	echo form_textarea($description);
}

/**
 * 
 * @param $masterDegreeData - Array with the data of the registered master degree
 */
function displayMasterDegreeData($masterDegreeData){
	$thereIsMasterDegree = $masterDegreeData != FALSE;

	echo "<br><h4><span class='label label-default'>Mestrado Acadêmico cadastrado</span></h4>";
	echo "<table class = 'table table-hover'>";
		echo "<tr>";
			echo "<th class='text-center'>Nome</th>";
			echo "<th class='text-center'>Duração</th>";
			echo "<th class='text-center'>Créditos totais</th>";
			echo "<th class='text-center'>Carga-horária</th>";
			echo "<th class='text-center'>Turma</th>";
			echo "<th class='text-center'>Descrição</th>";
		echo "</tr>";

	if($thereIsMasterDegree){

		echo "<tr>";
			echo "<td class='text-center'>".$masterDegreeData['course_name']."</td>";
			echo "<td class='text-center'>".$masterDegreeData['duration']." anos</td>";
			echo "<td class='text-center'>".$masterDegreeData['total_credits']."</td>";
			echo "<td class='text-center'>".$masterDegreeData['workload']."h</td>";
			echo "<td class='text-center'>".$masterDegreeData['start_class']."</td>";
			echo "<td class='text-center'>".$masterDegreeData['description']."</td>";
		echo "</tr>";
		echo "</table>";

	}else{
		echo "</table>";
		echo "<h4><span class='label label-danger'>Nenhum mestrado cadastrado para esse Programa Acadêmico.</span></h4>";
	}
}

function displayRegisteredDoctorateData($courseId, $haveMasterDegree, $doctorateData){
	$thereIsDoctorateDegree = $doctorateData != FALSE;

	echo "<br><h4><span class='label label-default'>Doutorado Acadêmico cadastrado</span></h4>";
	echo "<table class = 'table table-hover'>";
		echo "<tr>";
			echo "<th class='text-center'>Nome</th>";
			echo "<th class='text-center'>Duração</th>";
			echo "<th class='text-center'>Créditos totais</th>";
			echo "<th class='text-center'>Carga-horária</th>";
			echo "<th class='text-center'>Turma</th>";
			echo "<th class='text-center'>Descrição</th>";
		echo "</tr>";

	if($thereIsDoctorateDegree){

		echo "<tr>";
			// echo "<td class='text-center'>".$doctorateData['course_name']."</td>";
			echo "<td class='text-center'>".$doctorateData['duration']." anos</td>";
			echo "<td class='text-center'>".$doctorateData['total_credits']."</td>";
			echo "<td class='text-center'>".$doctorateData['workload']."h</td>";
			echo "<td class='text-center'>".$doctorateData['start_class']."</td>";
			echo "<td class='text-center'>".$doctorateData['description']."</td>";
		echo "</tr>";
		echo "</table>";

	}else{
		echo "</table>";
		echo "<h4><span class='label label-danger'>Nenhum doutorado cadastrado para esse Programa Acadêmico.</span></h4><br>";
		if($haveMasterDegree){
			echo anchor("registerDoctorateCourse/{$courseId}", 'Cadastrar Doutorado', array(
			"class" => "btn btn-primary"));
		}
	}
}

function emptyDiv(){
	echo "";
}