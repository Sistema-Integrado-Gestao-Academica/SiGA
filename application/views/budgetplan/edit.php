<h2 class="principal">Plano orçamentário</h2>

<?php  
echo form_open("budgetplan/update");
echo form_hidden("budgetplan_id", $budgetplan['id']);


echo form_label("Curso", "course");
echo "<br>";
echo form_dropdown('courses', $courses, $budgetplan['course_id']-1);

echo "<br>";

echo form_label("Montante", "amount");
echo form_input(array(
	"name" => "amount",
	"id" => "amount",
	"type" => "number",
	"class" => "form-campo",
	"value" => $budgetplan['amount'],
	"disabled" => $disable
));

echo form_label("Gasto", "spending");
echo form_input(array(
	"name" => "spending",
	"id" => "spending",
	"type" => "number",
	"class" => "form-campo",
	"value" => $budgetplan['spending']
));

echo form_label("Status", "status");
echo "<br>";
echo form_dropdown('status', $status, $budgetplan['status']-1);

echo "<br><br>";

echo form_button(array(
	"class" => "btn btn-primary",
	"type" => "sumbit",
	"content" => "Salvar"
));

echo form_close();
?>
