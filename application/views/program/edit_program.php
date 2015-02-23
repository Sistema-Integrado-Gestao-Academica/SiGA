
<?php 
	
$programName = array(
	"name" => "program_name",
	"id" => "program_name",
	"type" => "text",
	"class" => "form-campo",
	"class" => "form-control",
	"maxlength" => "40"
);

$programAcronym = array(
	"name" => "program_acronym",
	"id" => "program_acronym",
	"type" => "text",
	"class" => "form-campo",
	"class" => "form-control",
	"maxlength" => "6"
);

if($programData !== FALSE){
	$programName['value'] = $programData['program_name'];
	$programAcronym['value'] = $programData['acronym'];
}

$openingYear = array();
// GET THE CURRENT YEAR
$currentYear = 2015;
for($i = $currentYear; $i < $currentYear + 100; $i++ ){
	$openingYear[$i] = $i;
}

$submitBtn = array(
	"id" => "sregister_new_program",
	"class" => "btn bg-olive btn-block",
	"content" => "Editar programa",
	"type" => "submit"
);

?>

<div class="form-box" id="login-box">
	<div class="header">Editar Programa</div>
	<?= form_open("program/updateProgram") ?>
	<?= form_hidden("programId", $programData['id_program']) ?>

		<div class="body bg-gray">
			<div class="form-group">	
				<?= form_label("Nome do Programa", "program_name") ?>
				<?= form_input($programName) ?>
				<?= form_error("program_name") ?>
			</div>

			<div class="form-group">	
				<?= form_label("Sigla", "program_acronym") ?>
				<?= form_input($programAcronym) ?>
				<?= form_error("program_acronym") ?>
			</div>

			<div class="form-group">	
				<?= form_label("Coordenador", "program_coordinator") ?>
				<?= form_dropdown("program_coordinator", $users) ?>
				<?= form_error("program_coordinator") ?>
			</div>

			<div class="form-group">	
				<?= form_label("Ano de abertura", "opening_year") ?>
				<?= form_dropdown("opening_year", $openingYear, $programData['opening_year']) ?>
				<?= form_error("opening_year") ?>
			</div>

		</div>

		<div class="footer">
			<div class="row">
				<div class="col-xs-6">
					<?= form_button($submitBtn) ?>
				</div>
				<div class="col-xs-6">
					<?= anchor('cursos', 'Voltar', "class='btn bg-olive btn-block'") ?>
				</div>
			</div>
		</div>
	<?= form_close() ?>
</div>