<?php require_once (MODULESPATH."/program/constants/SelectionProcessConstants.php");  ?>


<?php

	$studentType = array(
		SelectionProcessConstants::REGULAR_STUDENT => 'Alunos Regulares',
		SelectionProcessConstants::SPECIAL_STUDENT => 'Alunos Especiais'
	);

?>

<!-- Basic data of selection process -->

<?= form_input($hidden); ?>

<h3><i class="fa fa-file-o"></i> Dados básicos</h3>
<br>

<div class="row">
	<div class="col-md-3">
		<?= form_label("Processo Seletivo para:", "student_type"); ?>
		<?= form_dropdown("student_type", $studentType, $selectedStudentType, "id='student_type' class='form-control'"); ?>
	</div>
	<div class="col-md-6">
		<?= form_label("Nome do edital", "selective_process_name"); ?>
		<?= form_input($name); ?>
	</div>
</div>

<br>
<br>

<!-- Applying period of selection process -->

<h4><i class="fa fa-calendar"></i> Período de inscrições</h4>
<br>

<div class="row">
	<div class="col-md-3">
		<?= form_label("Data de início do edital", "selective_process_start_date"); ?>
		<?= form_input($startDate); ?>
	</div>
	<div class="col-md-3">
		<?= form_label("Data final do edital", "selective_process_end_date"); ?>
		<?= form_input($endDate); ?>
	</div>
</div>
