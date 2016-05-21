
<?php 

$submitBtn = array(
	"id" => "sregister_new_program",
	"class" => "btn bg-olive btn-block",
	"content" => "Salvar alteração",
	"type" => "submit"
);

?>

<div class="form-box" id="login-box">
	<div class="header">Editar Programa</div>
	<?= form_open("program/updateProgramArea") ?>
	<?= form_hidden("programId", $programData['id_program']) ?>

		<div class="body bg-gray">
			<div class="form-group">	
				<?= form_label("Nome do Programa", "program_name") ?><br>
				<?= $programData['program_name']?>
			</div>

			<div class="form-group">	
				<?= form_label("Sigla", "program_acronym") ?><br>
				<?= $programData['acronym'] ?>
			</div>

			<div class="form-group">	
				<?= form_label("Área Atual", "actual_area") ?><br>
				<?= $currentArea['area_name'] ?>
			</div>

			<div class="form-group">	
				<?= form_label("Nova área do programa", "new_program_area") ?>
				<?= form_dropdown("new_program_area", $areas) ?>
				<?= form_error("new_program_area") ?>
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
