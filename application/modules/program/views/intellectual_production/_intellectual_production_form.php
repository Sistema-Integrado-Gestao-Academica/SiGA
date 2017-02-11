<div class="row">

	<div class="col-lg-7">

		<div class="form-group">
			<?= form_label("Título da produção", "title") ?>
			<?= form_input($title)?>
		</div>


		<div class="form-group">
			<?= form_label("Ano", "year") ?>
			<?= form_input($year)?>
		</div>

		<div class="form-group">
			<?= form_label("Tipo da produção", "type") ?>
			<?= form_dropdown("types", $types, $typeValue, ['class' => "form-control", 'id' => "types"]) ?>

		</div>

		<div class="form-group">
			<?= form_label("Subtipo da produção", "subtype") ?>
			<?= form_dropdown("subtypes", $subtypes, $subtypeValue, ['class' => "form-control", 'id' => "subtypes"]) ?>

		</div>
	</div>
	<div class="col-lg-5">

		<div class="form-group">
			<?= form_label("Projeto relacionado", "project") ?>
			<?= form_dropdown("projects", $projects, $projectValue, ['class' => "form-control", 'id' => "projects", "required" => "required"]) ?>

		</div>

		<div class="form-group">
			<?= form_label("Título do periódico", "periodic") ?>
			<?= form_input($periodic)?>
		</div>

		<div class="form-group">
			<?= form_label("ISSN ou ISBN", "identifier") ?>
			<?= form_input($identifier)?>					
		</div>

		<div class="form-group">
			<?= form_label("Qualis", "qualis") ?>
			<?= form_input($qualis)?>					
		</div>

	</div>	
	<div class="footer">
		<div class="row">
			<div class="col-lg-5" id="center_btn_form">
				<?= form_button(array(
					"class" => "btn bg-olive btn-block",
					"type" => "submit",
					"content" => "Salvar"
				)) ?>
			</div>
		</div>
	</div>
</div>