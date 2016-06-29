<h2 class="principal">Editar despesa</h2>

<?php

	$noteInput = array(
		"name" => "note",
		"id" => "note",
		"type" => "text",
		"class" => "form-control",
		"placeholder" => "Exemplo: 2011NE005787",
		"value" => $expense->getNote()
	);

	$dateInput = array(
		"name" => "expense_detail_emission_date",
		"id" => "expense_detail_emission_date",
		"type" => "text",
		"class" => "form-control",
		"value" => $expense->getDMYEmissionDate()
	);

	$seiInput = array(
		"name" => "sei_process",
		"id" => "sei_process",
		"type" => "text",
		"class" => "form-control",
		"value" => $expense->getSEIProcess()	
	);

	$valueInput = array(
		"name" => "value",
		"id" => "value",
		"type" => "number",
		"step" => 0.01, 
		"class" => "form-control",
		"required" => "required",
		"value" => $expense->getValue()
	);

	$descriptionInput = array(
		"name" => "description",
		"id" => "description",
		"type" => "text",
		"class" => "form-control",
		"value" => $expense->getDescription()
	);

	$id = $expense->getId();
?>

<div align="center">

<div class="form-box-logged">
	<?= form_open("update_expense_detail/{$id}") ?>

   	<?php include ('_form_expenses_expense.php');?>

	<?= form_close() ?>
</div>

</div>
