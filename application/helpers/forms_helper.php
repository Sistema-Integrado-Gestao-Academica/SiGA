<?php

function addDisciplinesToRequestForm($courseId, $userId){

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

	$academic_program_name = array(
		"name" => "program_name",
		"id" => "program_name",
		"type" => "text",
		"class" => "form-campo",
		"class" => "form-control",
		"maxlength" => "40"
	);

	echo "<h3><span class='label label-primary'>Programa Acadêmico</span></h3>";
	?>
	<div class='form-group'>
	<?php 
		echo form_label('Nome do Programa Acadêmico', 'program_name');
		echo form_input($academic_program_name);
	?>
	</div>	
	<?php 
	echo "<h4><span class='label label-default'>Mestrado Acadêmico</span></h4>";
	echo "<small> Para cadastrar um Doutorado acesse a página para editar um curso.</small>";
	
	commonAttrForPostGraduationCourses();
}

function professionalProgramForm(){
	
	$professional_program_name = array(
		"name" => "program_name",
		"id" => "program_name",
		"type" => "text",
		"class" => "form-campo",
		"class" => "form-control",
		"maxlength" => "40"
	);
	echo "<h3><span class='label label-primary'>Programa Profissional</span></h3>";
	?>
	<div class='form-group'>
		<?php
			echo form_label('Nome do Programa Professional', 'program_name');
			echo form_input($professional_program_name);
		?>
	</div>
	<?php
	echo "<h4><span class='label label-default'>Mestrado Profissional</span></h4>";

	commonAttrForPostGraduationCourses();
}

function masterDegreeProgramForm(){

	$masterDegreeName = array(
		"name" => "master_degree_name_update",
		"id" => "master_degree_name_update",
		"type" => "text",
		"class" => "form-campo",
		"class" => "form-control",
		"maxlength" => "40"
	);

	echo "<h4><span class='label label-default'>Mestrado Acadêmico - Alterar</span></h4>";
	?>
	<div class='form-group'>
		<?php
			echo form_label('Nome do mestrado', 'master_degree_name_update');
			echo form_input($masterDegreeName);
		?>
	</div>
		<?php
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
		"class" => "form-control",
		"maxlength" => "50"
	);

	$submit_button = array(
		"class" => "btn bg-olive btn-block",
		"content" => "Cadastrar Doutorado",
		"type" => "submit"
	);

	echo "<h4><span class='label label-default'>Doutorado Acadêmico</span></h4>";
	?>
	<div class='form-group'>
		<?php
			echo form_label('Nome do curso', 'doctorate_course_name');
			echo form_input($doctorate_course_name);
		?>
	</div>
	<?php
	
	commonAttrForPostGraduationCourses();
	
	echo "<br><br>";
	echo form_button($submit_button);
}

function formToUpdateDoctorateCourse(){
	
	$doctorate_course_name = array(
		"name" => "doctorate_course_name",
		"id" => "doctorate_course_name",
		"type" => "text",
		"class" => "form-campo",
		"class" => "form-control",
		"maxlength" => "50"
	);

	$submit_button = array(
		"class" => "btn bg-olive btn-block",
		"content" => "Alterar Doutorado",
		"type" => "submit"
	);

	echo "<h4><span class='label label-default'>Doutorado Acadêmico - Alterar</span></h4>";

	?>
	<div class='form-group'>
		<?php
			echo form_label('Nome do curso', 'doctorate_course_name');
			echo form_input($doctorate_course_name);
		?>
	</div>
	<?php
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
		"class" => "form-control"
	);

	$course_hours = array(
		'name' => 'course_hours',
		'id' => 'course_hours',
		'maxlength' => '10',
		"class" => "form-control"
	);

	$course_class = array(
		'name' => 'course_class',
		'id' => 'course_class',
		'placeholder' => 'Informe o semestre de início.',
		'maxlength' => '6',
		"class" => "form-control"
	);

	$description = array(
		'name' => 'course_description',
		'id' => 'course_description',
		'placeholder' => 'Informe a descrição do curso.',
		'rows' => '500',
		"class" => "form-control",
		'style' => 'height: 100px;'
	);
?>
	<div class="form-group">
		<?php 
		// Course duration field
		echo form_label('Duração do curso ', 'course_duration');
		echo form_dropdown('course_duration', $course_duration, '2', 'id=course_duration');
		?>
	</div>
	<div class="form-group">
		<?php
		// Course total credits field
		echo form_label('Créditos totais', 'total_credits');
		echo form_input($total_credits);
		?>
	</div>
	<div class="form-group">
		<?php
		// Course hours field
		echo form_label('Carga-horária total', 'course_hours');
		echo form_input($course_hours);
		?>
	</div>
	<div class="form-group">
		<?php
		// Course class field
		echo form_label('Turma', 'course_class');
		echo form_input($course_class);
		?>
	</div>
	<div class="form-group">
		<?php
		// Course description field
		echo form_label('Descrição ', 'course_description');
		echo form_textarea($description);
		?>
	</div>
	<?php 	
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
			echo "<td class='text-center'>".$masterDegreeData['master_degree_name']."</td>";
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

function displayRegisteredDoctorateData($courseId, $haveMasterDegree, $haveDoctorate, $doctorateData){
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
			echo "<td class='text-center'>".$doctorateData['doctorate_name']."</td>";
			echo "<td class='text-center'>".$doctorateData['duration']." anos</td>";
			echo "<td class='text-center'>".$doctorateData['total_credits']."</td>";
			echo "<td class='text-center'>".$doctorateData['workload']."h</td>";
			echo "<td class='text-center'>".$doctorateData['start_class']."</td>";
			echo "<td class='text-center'>".$doctorateData['description']."</td>";
		echo "</tr>";
		echo "</table>";

		if($haveDoctorate){
			echo anchor(
				"updateDoctorateCourse/{$courseId}",
				'Alterar Doutorado',
				array(
					"class" => "btn btn-primary",
					"id" => "update_doctorate_btn"
				)
			);

			echo anchor(
				"/course/removeDoctorateCourse/{$courseId}",
				'Remover Doutorado',
				array(
					"class" => "btn btn-danger",
					"id" => "remove_doctorate_btn"
				)
			);
		}

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