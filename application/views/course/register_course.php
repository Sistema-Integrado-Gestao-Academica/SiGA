<h2 class="text-center">Cadastro de um novo curso</h2>
<input id="site_url" name="site_url" type="hidden" value="<?php echo $url; ?>"></input>

<?php
$course = new Course();

$form_course_type = $course->getCourseTypes();

$course_name_array_to_form = array(
		"name" => "courseName",
		"id" => "courseName",
		"type" => "text",
		"class" => "form-campo",
		"maxlength" => "50",
		"value" => set_value("nome", ""),
);

$submit_button_array_to_form = array(
		"class" => "btn btn-primary",
		"content" => "Cadastrar",
		"type" => "submit"
);

echo form_open("course/newCourse");

	// Name field
	echo form_label("Nome do Curso", "courseName");
	echo form_input($course_name_array_to_form);
	echo form_error("courseName");
	echo "<br>";
	
	// User type field
	echo form_label("Tipo de Curso", "courseTypeLabel");
	echo form_dropdown("courseType", $form_course_type, '', "id='courseType'");
	echo form_error("courseType");
	echo "<br>";
	
	?>
	<br><div id="post_grad_types"></div>
	<br><div id="chosen_post_grad_type"></div>
	<?php
	
	// Submit button
	echo "<br>";
	echo form_button($submit_button_array_to_form);

echo form_close();
