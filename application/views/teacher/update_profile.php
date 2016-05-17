
<?php 
	
$summaryField = array(
	"name" => "summaryField",
	"id" => "summaryField",
	"type" => "text",
	"class" => "form-campo",
	"class" => "form-control",
	"maxlength" => "4000",
	"placeholder" => "Máximo de 4000 caracteres",

);

$lattesField = array(
	"name" => "lattesField",
	"id" => "lattesField",
	"type" => "url",
	"class" => "form-campo",
	"class" => "form-control"
);

$submitBtn = array(
	"id" => "update_profile",
	"class" => "btn bg-olive btn-block",
	"content" => "Atualizar perfil",
	"type" => "submit"
);

// Setting values
$summaryField['value'] = $summary;
$lattesField['value'] = $lattes;
$researchLineField['value'] = $researchLine;

?>

<div class="form-box" id="login-box">
	<div class="header">Atualizar Perfil</div>
	<?= form_open("program/teacher/saveProfile") ?>
	<?= form_hidden("teacher", $teacher) ?>
	<?= form_hidden("researchLines", $availableResearchLines) ?>


		<div class="body bg-gray">
			<div class="form-group">	
				<?= form_label("Resumo", "summaryField") ?>
				<?= form_textarea($summaryField) ?>
				<?= form_error("summaryField") ?>
			</div>

			<div class="form-group">	
				<?= form_label("Link para o Lattes", "lattesField") ?>
				<?= form_input($lattesField) ?>
				<?= form_error("lattesField") ?>
			</div>

			<div class="form-group">	
				<?= form_label("Linha de Pesquisa", "researchLineField") ?>
				<?= form_dropdown("researchLineField", $availableResearchLines, '', "id='researchLineField'") ?>
				<?= form_error("researchLineField") ?>
			</div>

		</div>

		<div class="footer">
			<div class="row">
				<div class="col-xs-6">
					<?= form_button($submitBtn) ?>
				</div>
				<div class="col-xs-6">
					<?php 
						echo anchor('mastermind_home', 'Voltar', "class='btn bg-olive btn-block'");
					?>

				</div>
			</div>
		</div>
	<?= form_close() ?>
</div>
