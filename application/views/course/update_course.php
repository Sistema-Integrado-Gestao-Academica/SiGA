<h2 class="principal">Cursos</h2>
<input id="site_url" name="site_url" type="hidden" value="<?=$url?>">
<input id="current_course" type="hidden" value="<?=$course['id_course']?>">

<?php
$hidden = array(
	'id_course' => $course['id_course'], 
	'id_secretary' => $secretary_registered['id_secretary'], 
	'original_course_type' => $course['course_type']
);

$course_name_array_to_form = array(
		"name" => "courseName",
		"id" => "courseName",
		"type" => "text",
		"class" => "form-campo",
		"maxlength" => "50",
		"value" => set_value("nome", $course['course_name']),
		"style" => "width: 40%;"
);

$submit_button_array_to_form = array(
		"class" => "btn btn-primary",
		"content" => "Alterar",
		"type" => "submit"
);
?>

<div class="row">
	<div class="col-lg-12">
		<?= form_open("course/updateCourse", '', $hidden) ?>
			
			<?= form_label("Nome do Curso", "courseName") ?>
			<?= form_input($course_name_array_to_form) ?>
			<?= form_error("courseName") ?>

			<br><br>
			<h3><span class="label label-primary">Secretaria</span></h3>
			<br>

			<?= form_label("Tipo de Secretaria", "secreteary_type") ?>
			<?= form_dropdown("secretary_type", $form_groups, $secretary_registered['id_group']) ?>
			<?= form_error("secretary_type") ?>

			<br><br>

			<?= form_label("Escolher secretário", "user_secretary") ?>
			<?= form_dropdown("user_secretary", $form_user_secretary, $secretary_registered['id_user'], "id='user_secretary'") ?>
			<?= form_error("user_secretary") ?>

			<br><br>
			<h3><span class="label label-primary">Tipo de Curso</span></h3>
			<br>

			<?= form_label("Tipo de Curso", "courseType") ?>
			<?= form_dropdown("courseType", $form_course_type, $course['course_type_id'], "id='courseType'") ?>
			<?= form_error("courseType") ?>

			<br><br><br>

			<?= form_button($submit_button_array_to_form) ?>
		<?= form_close() ?>
	</div>
</div>
