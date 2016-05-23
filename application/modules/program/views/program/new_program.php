
<?php 
require_once MODULESPATH.'program/controllers/Program.php';

$programName = array(
	"name" => "program_name",
	"id" => "program_name",
	"type" => "text",
	"class" => "form-campo",
	"class" => "form-control",
	"maxlength" => "80"
);

$programAcronym = array(
	"name" => "program_acronym",
	"id" => "program_acronym",
	"type" => "text",
	"class" => "form-campo",
	"class" => "form-control",
	"maxlength" => "6"
);


$submitBtn = array(
	"id" => "sregister_new_program",
	"class" => "btn bg-olive btn-block",
	"content" => "Cadastrar programa",
	"type" => "submit"
);

if($programArea === FALSE){
	$programArea =  array("Nenhuma área cadastrada.");
}

$openingYear = array();
$currentYear = getCurrentYear();
if($currentYear !== FALSE){

	for($i = 1990; $i <= $currentYear + 2; $i++ ){
		$openingYear[$i] = $i;
	}
}else{
	$openingYear[] = "Ocorreu um erro ao ler o ano do banco de dados";
	$submitBtn['disabled'] = TRUE;
}

?>

<div class="form-box" id="login-box">
	<div class="header">Cadastrar um novo Programa</div>
	<?= form_open("program/newProgram") ?>
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
				<?php
					if($users !== FALSE){
				 		echo form_dropdown("program_coordinator", $users);
					}else{
						$submitBtn['disabled'] = TRUE;
				 		echo form_dropdown("program_coordinator", array('Não há nenhum coordenador cadastrado'));
					}
				?>
				<?= form_error("program_coordinator") ?>
			</div>

			<div class="form-group">
				<?= form_label("Ano de abertura", "opening_year") ?>
				<?= form_dropdown("opening_year", $openingYear, $currentYear) ?>
				<?= form_error("opening_year") ?>
			</div>

			<div class="form-group">
				<?= form_label("Área do programa", "program_area") ?>
				<?= form_dropdown("program_area", $programArea) ?>
				<?= form_error("program_area") ?>
			</div>

		</div>

		<div class="footer">
			<div class="row">
				<div class="col-xs-6">
					<?= form_button($submitBtn) ?>
				</div>
				<div class="col-xs-6">
					<?= anchor('program', 'Voltar', "class='btn bg-olive btn-block'") ?>
				</div>
			</div>
		</div>
	<?= form_close() ?>

	<?php if($users == FALSE) {
		echo "<div class='callout callout-danger'>";
			echo "<h4>Não é possível cadastrar um programa sem um coordenador.</h4>";
			echo "<p>Contate o administrador para o cadastro.</p>";
		echo "</div>";
	}
	?>

</div>
