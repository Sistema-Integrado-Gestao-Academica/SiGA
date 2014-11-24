<h2 class="text-center">Cadastro de um novo curso</h2>
<?php
$course = new Course();

$form_course_type = $course->getCourseTypes();

$course_name_array_to_form = array(
		"name" => "courseName",
		"id" => "courseName",
		"type" => "text",
		"class" => "form-campo",
		"maxlength" => "50",
		"value" => set_value("nome", "")
);

$submit_button_array_to_form = array(
		"class" => "btn btn-primary",
		"content" => "Cadastrar",
		"type" => "submit"
);

$form_array_finatiated = array(
		"name" => "finantiated",
		"id"   => "finantiated",
		"value"=> TRUE,
		"checked"=> FALSE
);

echo form_open("course/newCourse");

	// Name field
	echo form_label("Nome do Curso", "courseName");
	echo form_input();
	echo form_error("courseName");
	echo "<br>";
	
	// User type field
	echo form_label("Tipo de Curso", "courseType");
	echo form_dropdown("courseType",$form_course_type);
	echo form_error("courseType");
	echo "<br>";
	
	echo form_label("Financiado", "financiated");
	echo form_checkbox($form_array_finatiated);
	
	// Submit button
	echo "<br>";
	echo form_button($submit_button_array_to_form);

echo form_close();
