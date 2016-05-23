<?php
$discipline_name_to_form = array(
		"name" => "discipline_name",
		"id" => "discipline_name",
		"type" => "text",
		"class" => "form-campo",
		"maxlength" => "70",
		"class" => "form-control",
		"required" => TRUE
);

$discipline_code_to_form = array(
		"name" => "discipline_code",
		"id" => "discipline_code",
		"type" => "number",
		"min" => 1,
		"class" => "form-campo",
		"maxlength" => "8",
		"class" => "form-control",
		"required" => TRUE
);

$discipline_abbreviation_name_to_form = array(
		"name" => "name_abbreviation",
		"id" => "name_abbreviation",
		"type" => "text",
		"class" => "form-campo",
		"maxlength" => "6",
		"class" => "form-control",
		"required" => TRUE
);

$discipline_credits_to_form = array(
		"name" => "credits",
		"id" => "credits",
		"type" => "number",
		"min" => 2,
		"class" => "form-campo",
		"maxlength" => "2",
		"class" => "form-control",
		"required" => TRUE
);


$submit_button_array_to_form = array(
		"class" => "btn bg-olive btn-block",
		"content" => "Cadastrar",
		"type" => "submit"
);

?>

<div class="form-box" id="login-box">
	<div class="header">Cadastrar nova Disciplina</div>
	<?php
	echo form_open("program/discipline/newDiscipline");
	?>
	<div class="body bg-gray">

		<div class="form-group">
				<?= form_label("Curso pertencente", "course_prolongs") ?>
				<?= form_dropdown("course_prolongs", $courses) ?>
				<?= form_error("course_prolongs") ?>
		</div>

		<div class="form-group">
				<?php
				// Name field
				echo form_label("Nome da disciplina", "discipline_name");
				echo form_input($discipline_name_to_form);
				echo form_error("discipline_name");
				?>
		</div>

		<div class="form-group">
			<?php
			// Code field
			echo form_label("Código da disciplina", "discipline_code");
			echo form_input($discipline_code_to_form);
			echo form_error("discipline_code");
			?>
		</div>

		<div class="form-group">
			<?php
			// Acronym field
			echo form_label("Abreviação", "name_abbreviation");
			echo form_input($discipline_abbreviation_name_to_form);
			echo form_error("name_abbreviation");
			?>
		</div>

		<div class="form-group">
			<?php
			// Credits type field
			echo form_label("Quantidade de Créditos", "credits");
			echo form_input($discipline_credits_to_form);
			echo form_error("credits");
			?>
		</div>


	</div>
	<div class="footer">
		<?php
		// Submit button
		echo form_button($submit_button_array_to_form);
		echo form_close();
		?>
	</div>

</div>