<?php require_once (MODULESPATH."/program/constants/SelectionProcessConstants.php");  ?>


<?php

	$studentType = array(
		SelectionProcessConstants::REGULAR_STUDENT => 'Alunos Regulares',
		SelectionProcessConstants::SPECIAL_STUDENT => 'Alunos Especiais'
	);

	$class = $canNotEdit ? 'form-control disabled' : 'form-control';

?>

<!-- Basic data of selection process -->

<?= form_input($hidden); ?>

<h3><i class="fa fa-file-o"></i> Dados básicos</h3>
<br>

<div class="row">
	<div class="col-md-6">
		<?= form_label("Nome do edital", "selective_process_name"); ?>
		<?= form_input($name); ?>
	</div>
	<div class="col-md-3">
		<?= form_label("Processo Seletivo para:", "student_type"); ?>
		<?= form_dropdown("student_type", $studentType, $selectedStudentType, "id='student_type' class={$class}"); ?>
	</div>
</div>
<br>
<div class="row">
	<div class="col-md-4">
		<?= form_label("Número de vagas:", "vacancies"); ?>
		<?= form_input($vacancies); ?>
	</div>
	<div class="col-md-4">
		<?= form_label("Nota de corte do processo:", "vacancies"); ?>
		<?= form_input($passingScore); ?>
	</div>
</div>

<br>
<br>

