<?php

$courseName = array(
		"name" => "courseName",
		"id" => "courseName",
		"type" => "text",
		"class" => "form-campo",
		"class" => "form-control",
		"maxlength" => "50"
);

$courseDuration = array(
	'2' => '2 anos',
	'4' => '4 anos'
);

$totalCredits = array(
	'name' => 'course_total_credits',
	'id' => 'course_total_credits',
	"class" => "form-control",
	"type" => "number",
	'min' => '1'
);

$courseHours = array(
	'name' => 'course_hours',
	'id' => 'course_hours',
	"class" => "form-control",
	"type" => "number",
	'min' => '1'
);

$courseClass = array(
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

$submitBtn = array(
		"class" => "btn bg-olive btn-block",
		"type" => "sumbit",
		"content" => "Cadastrar"
);

if($registeredPrograms !== FALSE){
	// Nothing to do because there are programs to associate to a course
	$thereAreNoPrograms = FALSE;
}else{
	$thereAreNoPrograms = TRUE;

	$submitBtn['disabled'] = TRUE;
	$registeredPrograms = array("Nenhum programa cadastrado.");
}
?>

<div class="form-box" id="login-box">

	<div class="header">Cadastrar um novo Curso</div>
	<?= form_open("program/course/newCourse") ?>
		<div class="body bg-gray">
			<div class="form-group">	
				<?= form_label("Nome do Curso", "courseName") ?>
				<?= form_input($courseName) ?>
				<?= form_error("courseName") ?>
			</div>

			<div class="form-group">	
				<?= form_label("Tipo de Curso", "courseType") ?>
				<?= form_dropdown("courseType", $form_course_types, '', "id='courseType'") ?>
				<?= form_error("courseType") ?>
			</div>

			<div class="form-group">	
				<?= form_label("Programa do curso", "courseProgram") ?>
				<?= form_dropdown("courseProgram", $registeredPrograms, '', "id='courseProgram'") ?>
				<?= form_error("courseProgram") ?>
			</div>

			<div class="form-group">
				<?php 
				// Course duration field
				echo form_label('Duração do curso ', 'course_duration');
				echo form_dropdown('course_duration', $courseDuration, '2', 'id=course_duration');
				?>
			</div>

			<div class="form-group">
				<?php
				// Course total credits field
				echo form_label('Créditos totais', 'course_total_credits');
				echo form_input($totalCredits);
				?>
			</div>

			<div class="form-group">
				<?php
				// Course hours field
				echo form_label('Carga-horária total', 'course_hours');
				echo form_input($courseHours);
				?>
			</div>

			<div class="form-group">
				<?php
				// Course class field
				echo form_label('Turma', 'course_class');
				echo form_input($courseClass);
				?>
			</div>
			
			<div class="form-group">
				<?php
				// Course description field
				echo form_label('Descrição ', 'course_description');
				echo form_textarea($description);
				?>
			</div>
		</div>

		<div class="footer">
			<div class="row">
				<div class="col-xs-6">
					<?= form_button($submitBtn) ?>
				</div>
				<div class="col-xs-6">
					<?= anchor('cursos', 'Voltar', "class='btn bg-olive btn-block'") ?>
				</div>
			</div>
		</div>
	<?= form_close() ?>

	<?php if($thereAreNoPrograms){ ?>
		<div class="callout callout-danger">
			<h4>Não é possível cadastrar um curso sem um programa.</h4>
		</div>
	<?php } ?>
</div>