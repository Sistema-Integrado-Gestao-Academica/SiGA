<?php

$submitBtn = array(
		"id" => "update_titling",
		"class" => "btn bg-olive btn-block",
		"content" => "Atualizar titulação",
		"type" => "submit"
);


?>

<div class="form-box" id="login-box">
	<div class="header">Atualizar Área de Título</div>
	<?= form_open("program/mastermind/UpdateTitlingArea") ?>
		<div class="body bg-gray">
			
			<div class="form-group">	
				<?= form_label("Área de titulação", "titling_area") ?>
				<?= form_dropdown("titling_area", $areas, $currentArea) ?>
				<?= form_error("titling_area") ?>
			</div>
			
			<div class="form-group">	
				<?= form_label("Tese de doutorado claramente na <br>tematica da área", "titling_thesis") ?>
				<?= form_checkbox("titling_thesis", TRUE)?>
				<?= form_error("titling_thesis") ?>
			</div>
		</div>

		<div class="footer">
			<div class="row">
				<div class="col-xs-6">
					<?= form_button($submitBtn) ?>
				</div>
				<div class="col-xs-6">
					<?= anchor('mastermind_home', 'Voltar', "class='btn bg-olive btn-block'") ?>
				</div>
			</div>
		</div>
	<?= form_close() ?>

</div>