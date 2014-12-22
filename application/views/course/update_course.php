<h2 class="principal">Cursos</h2>
<input id="site_url" name="site_url" type="hidden" value="<?php echo $url; ?>" />
<input id="current_course" type="hidden" value="<?php echo $course->id_course; ?>" />

<?php 
require_once APPPATH.'controllers/module.php';
require_once APPPATH.'controllers/usuario.php';

$course_name = $course->course_name;
$course_id = $course->id_course;
$original_course_type = $course->course_type;

$group = new Module();

$form_groups = $group->getExistingModules();

$course_controller = new Course();

$secretary_registered = $course_controller->getCourseSecrecretary($course_id);

$hidden = array('id_course' => $course_id, 
				'id_secretary'=>$secretary_registered['id_secretary'], 
				'original_course_type'=> $original_course_type);

$user = new Usuario();

$form_user_secretary = $user->getAllSecretaryUsers();


$form_course_type = array(

	'graduation' => 'Graduação',
	'ead' => 'Educação a distância',
	'post_graduation' => 'Pós-Graduação'
);

$course_name_array_to_form = array(
		"name" => "courseName",
		"id" => "courseName",
		"type" => "text",
		"class" => "form-campo",
		"maxlength" => "50",
		"value" => set_value("nome", $course_name),
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
<?php
echo form_open("course/updateCourse",'',$hidden);

	// Name field
	echo form_label("Nome do Curso", "courseName");
	echo form_input($course_name_array_to_form);
	echo form_error("courseName");
	echo "<br>";
	echo "<br>";
	
	?>
	<h3><span class="label label-primary">Secretaria</span></h3>
	<br>
	
	<?php 
	//secretary field
	echo form_label("Tipo de Secretaria", "secreteary_type");
	echo form_dropdown("secretary_type", $form_groups, $secretary_registered['id_group']);
	echo form_error("secretary_type");
	echo "<br>";
	echo "<br>";
	
	echo form_label("Escolher secretário", "user_secretary");
	echo form_dropdown("user_secretary", $form_user_secretary, $secretary_registered['id_user'], "id='user_secretary'");
	echo form_error("user_secretary");
	echo "<br>";
	echo "<br>";
	
	?>
	<h3><span class="label label-primary">Tipo de Curso</span></h3>
	<br>
	
	<?php 
	// User type field
	echo form_label("Tipo de Curso", "courseType");
	echo form_dropdown("courseType", $form_course_type, '', "id='courseType'");
	echo form_error("courseType");
	echo "<br>";

	?>
	<br><div id="post_grad_types"></div>
	
	<div class="row">
		<div class="col-lg-6">
			<div class="row">
				<div id="registered_master_degree"></div>
			</div>
			<div class="row">
				<div id="registered_doctorate"></div>
			</div>
		</div>
		<div class="col-lg-6">
			<br><div id="update_master_degree"></div>
		</div>
	</div>
	<?php

	// Submit button
	echo "<br>";
	echo form_button($submit_button_array_to_form);

echo form_close();
?>
</div>


</div>
