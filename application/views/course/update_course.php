<h2 class="principal">Cursos</h2>
<input id="site_url" name="site_url" type="hidden" value="<?php echo $url; ?>"></input>

<?php  
$course_name = $course->course_name;
$course_id = $course->id_course;
$course_type = $course->course_type_id;


$course_controller = new Course();

$hidden = array("id_course" => $course_id);

$form_course_type = $course_controller->getCourseTypes();

$course_name_array_to_form = array(
		"name" => "courseName",
		"id" => "courseName",
		"type" => "text",
		"class" => "form-campo",
		"maxlength" => "50",
		"value" => set_value("nome", $course_name)
);

$submit_button_array_to_form = array(
		"class" => "btn btn-primary",
		"content" => "Alterar",
		"type" => "submit"
);

echo form_open("course/updateCourse",'',$hidden);

	// Name field
	echo form_label("Nome do Curso", "courseName");
	echo form_input($course_name_array_to_form);
	echo form_error("courseName");
	echo "<br>";

	// User type field
	echo form_label("Tipo de Curso", "courseType");
	echo form_dropdown("courseType", $form_course_type, $course_type, "id='courseType'");
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

?>
