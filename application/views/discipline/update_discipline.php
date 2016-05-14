<h2 class="principal">Disciplinas</h2>
<input id="site_url" name="site_url" type="hidden" value="<?php echo $url; ?>" />
<input id="current_discipline" type="hidden" value="<?php echo $discipline['discipline_code']; ?>" />

<?php 

$discipline_name_to_form = array(
		"name" => "discipline_name",
		"id" => "discipline_name",
		"type" => "text",
		"class" => "form-campo",
		"maxlength" => "70",
		"class" => "form-control",
		"value" => $discipline['discipline_name']
);

$discipline_code_hidden = array(
		'discipline_code' => $discipline['discipline_code']
);

$discipline_abbreviation_name_to_form = array(
		"name" => "name_abbreviation",
		"id" => "name_abbreviation",
		"type" => "text",
		"class" => "form-campo",
		"maxlength" => "8",
		"class" => "form-control",
		"value" => $discipline['name_abbreviation']
);

$discipline_credits_to_form = array(
		"name" => "credits",
		"id" => "credits",
		"type" => "text",
		"class" => "form-campo",
		"maxlength" => "2",
		"class" => "form-control",
		"value" => $discipline['credits']
);

$discipline_workload_to_form = array(
		"name" => "workload",
		"id" => "workload",
		"type" => "text",
		"class" => "form-campo",
		"maxlength" => "3",
		"class" => "form-control",
		"value" => $discipline['workload']
);

$submit_button_array_to_form = array(
		"class" => "btn bg-olive btn-block",
		"content" => "Alterar",
		"type" => "submit"
);

?>

<div class="form-box" id="login-box"> 
	<div class="header">Alterar Disciplina</div>
	<?php
	echo form_open("program/discipline/updateDiscipline",'',$discipline_code_hidden);
	?>
	<div class="body bg-gray">
		<div class="form-group">
				<?php 
				// Name field
				echo form_label("Nome a disciplina", "discipline_name");
				echo form_input($discipline_name_to_form);
				echo form_error("discipline_name");
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
		
		<div class="form-group">
			<?php
			// Workload type field
			echo form_label("Carga Horaria Semestral", "workload");
			echo form_input($discipline_workload_to_form);
			echo form_error("workload");
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