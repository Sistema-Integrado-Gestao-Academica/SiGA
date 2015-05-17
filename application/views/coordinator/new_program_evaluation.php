<?php

$currentYear = getCurrentYear();

$startYear = array(
	'name' => 'evaluation_start_year',
	'id' => 'evaluation_start_year',
	"class" => "form-control",
	"type" => "number"
);

if($currentYear !== FALSE){
	$startYear['min'] = $currentYear;
}

$endYear = array(
	'name' => 'evaluation_end_year',
	'id' => 'evaluation_end_year',
	"class" => "form-control",
	"type" => "number"
);

if($currentYear !== FALSE){
	$endYear['min'] = $currentYear;
}

$submitBtn = array(
	"class" => "btn bg-olive btn-block",
	"type" => "submit",
	"content" => "Cadastrar"
);
?>

<div class="form-box">

	<div class="header">Cadastrar nova avaliação</div>
	<?= form_open("coordinator/newEvaluation") ?>
		<div class="body bg-gray">

			<?php echo form_hidden('programId', $programData['id_program']); ?>

			<b>Programa: <i><?php echo $programData['acronym']." - ".$programData['program_name']; ?></i></b>
			<br>
			<b>Ano atual</b>:  <?php echo $currentYear; ?>

			<div class="form-group">
				<?php
				// Evaluation start year field
				echo form_label('Ano de início da avaliação', 'evaluation_start_year');
				echo form_input($startYear);
				?>
			</div>

			<div class="form-group">
				<?php
				// Evaluation end year field
				echo form_label('Ano de fim da avaliação', 'evaluation_end_year');
				echo form_input($endYear);
				?>
			</div>
		</div>

		<div class="footer">
			<div class="row">
				<div class="col-xs-6">
					<?= form_button($submitBtn) ?>
				</div>
				<div class="col-xs-6">
					<?= anchor('coordinator/coordinator_programs', 'Voltar', "class='btn bg-olive btn-block'") ?>
				</div>
			</div>
		</div>
	<?= form_close() ?>
</div>