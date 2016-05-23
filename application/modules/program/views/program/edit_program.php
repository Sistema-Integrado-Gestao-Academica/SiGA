<?php 

require_once(MODULESPATH."/auth/constants/GroupConstants.php");

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

$programContact = array(
	"name" => "program_contact",
	"id" => "program_contact",
	"type" => "text",
	"class" => "form-campo",
	"class" => "form-control",
	"maxlength" => "300",
	"placeholder" => "Máximo de 300 caracteres",
	"style" => "height: 70px",
	"cols" => "10"
);

$programSummary = array(
	"name" => "program_summary",
	"id" => "program_summary",
	"type" => "text",
	"class" => "form-campo",
	"class" => "form-control",
	"maxlength" => "1500",
	"placeholder" => "Máximo de 1500 caracteres",
	"style" => "height: 70px",
	"cols" => "10"
);

$programHistory = array(
	"name" => "program_history",
	"id" => "program_history",
	"type" => "text",
	"class" => "form-campo",
	"class" => "form-control",
	"maxlength" => "1500",
	"placeholder" => "Máximo de 1500 caracteres",
	"style" => "height: 70px",
	"cols" => "10"
);

if($programData !== FALSE){
	$programName['value'] = $programData['program_name'];
	$programAcronym['value'] = $programData['acronym'];
	$programContact['value'] = $programData['contact'];
	$programSummary['value'] = $programData['summary'];
	$programHistory['value'] = $programData['history'];

}

$submitBtn = array(
	"id" => "edit_program",
	"class" => "btn bg-olive btn-block",
	"content" => "Editar programa",
	"type" => "submit"
);

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
	<div class="header">Editar Programa</div>
	<?= form_open("program/program/updateProgram") ?>
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
				<?php
					if($users !== FALSE){
						echo form_dropdown("program_coordinator", $users, $programData['coordinator']);
					}else{
						$submitBtn['disabled'] = TRUE;
						echo form_dropdown("program_coordinator", array('Não há nenhum coordenador cadastrado.'));
					}
				?>
				<?= form_error("program_coordinator") ?>
			</div>

			<div class="form-group">
				<?= form_label("Ano de abertura", "opening_year") ?>
				<?= form_dropdown("opening_year", $openingYear, $programData['opening_year']) ?>
				<?= form_error("opening_year") ?>
			</div>

			<div class="form-group">
				<?= form_label("Contato", "program_contact") ?>
				<?= form_textarea($programContact)?>
				<?= form_error("program_contact") ?>
			</div>

			<div class="form-group">
				<?= form_label("Resumo", "program_summary") ?>
				<?= form_textarea($programSummary)?>
				<?= form_error("program_summary") ?>
			</div>

			<div class="form-group">
				<?= form_label("Histórico", "program_history") ?>
				<?= form_textarea($programHistory)?>
				<?= form_error("program_history") ?>
			</div>
		</div>

		<div class="footer">
			<div class="row">
				<div class="col-xs-6">
					<?= form_button($submitBtn) ?>
				</div>
				<div class="col-xs-6">
					<?php
					if ($userGroup == GroupConstants::ACADEMIC_SECRETARY_GROUP){
						echo anchor('secretary_programs', 'Voltar', "class='btn bg-olive btn-block'");
					}
					else{
						echo anchor('program', 'Voltar', "class='btn bg-olive btn-block'");
					} ?>

				</div>
			</div>
		</div>
	<?= form_close() ?>
</div>

<?php if($users == FALSE) {
	echo "<div class='callout callout-danger'>";
		echo "<h4>Não é possível editar um programa para que fique sem um coordenador.</h4>";
		echo "<p>Contate o administrador.</p>";
	echo "</div>";
}
?>

<br>
<br>

<h3> Adicionar cursos ao programa </h3>

<?php displayRegisteredCoursesToProgram($programData['id_program'], $courses)?>
