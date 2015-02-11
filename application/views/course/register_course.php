<?php
$course_name_array_to_form = array(
		"name" => "courseName",
		"id" => "courseName",
		"type" => "text",
		"class" => "form-campo",
		"class" => "form-control",
		"maxlength" => "50",
		"value" => set_value("nome", ""),
);

$submit_button_array_to_form = array(
		"class" => "btn bg-olive btn-block",
		"type" => "sumbit",
		"content" => "Cadastrar"
);
?>

<div class="form-box" id="login-box">
	<div class="header">Cadastrar um novo Curso</div>
	<?= form_open("course/newCourse") ?>
		<div class="body bg-gray">
			<div class="form-group">	
				<?= form_label("Nome do Curso", "courseName") ?>
				<?= form_input($course_name_array_to_form) ?>
				<?= form_error("courseName") ?>
			</div>

			<div class="form-group">	
				<?= form_label("Tipo de Curso", "courseTypeLabel") ?>
				<?= form_dropdown("courseType", $form_course_types, '', "id='courseType'") ?>
				<?= form_error("courseType") ?>
			</div>

			<div class="form-group">	
				<?= form_label("Tipo de Secretaria", "secreteary_type") ?>
				<?= form_dropdown("secretary_type", $form_groups) ?>
				<?= form_error("secretary_type") ?>
			</div>

			<div class="form-group">	
				<?= form_label("Escolher secretário", "user_secretary") ?>
				<?= form_dropdown("user_secretary", $form_user_secretary) ?>
				<?= form_error("user_secretary") ?>
			</div>

			<div class="form-group">	
				<div id="post_grad_types"></div>
			</div>

			<div class="form-group">	
				<div id="chosen_post_grad_type"></div>
			</div>
		</div>

		<div class="footer">
			<div class="row">
				<div class="col-xs-6">
					<?= form_button($submit_button_array_to_form) ?>
				</div>
				<div class="col-xs-6">
					<?= anchor('cursos', 'Voltar', "class='btn bg-olive btn-block'") ?>
				</div>
			</div>
		</div>
	<?= form_close() ?>
</div>