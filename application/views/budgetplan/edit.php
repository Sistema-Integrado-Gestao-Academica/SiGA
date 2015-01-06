<h2 class="principal">Plano orçamentário</h2>

<?php  
echo form_open("budgetplan/update");
echo form_hidden("budgetplan_id", $budgetplan['id']);
echo form_hidden("confirm");


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
	$disable_amount => $disable_amount
));

echo form_label("Gasto", "spending");
echo form_input(array(
	"name" => "spending",
	"id" => "spending",
	"type" => "number",
	"class" => "form-campo",
	"value" => $budgetplan['spending'],
	$disable_spending => $disable_spending
));

if ($budgetplan['status'] != 4) {
	echo form_label("Status", "status");
	echo "<br>";
	echo form_dropdown('status', $status, $budgetplan['status']-1, 'id="status"');

	echo "<br><br>";

	echo form_button(array(
		"class" => "btn btn-primary",
		"type" => "sumbit",
		"content" => "Salvar",
		"onclick" => "confirmation()"
	));
} else {
	echo "<br><a href='".base_url('plano%20orcamentario')."' class='btn btn-primary'>Voltar</a>";
}

echo form_close();
?>

<script>
	function confirmation() {
		var status = document.getElementById("status").value;
		if (status == 2) {
			if (confirm("ATENÇÃO! Com status \"Em execução\", não será mais possível alterar o valor do montante.")) {
				document.getElementsByName("confirm")[0].value = false;
			}
		} else if (status == 3) {
			if (confirm("ATENÇÃO! Com status \"Finalizado\", não será mais possível fazer alterações.")) {
				document.getElementsByName("confirm")[0].value = false;
			}
		}
	}
</script>