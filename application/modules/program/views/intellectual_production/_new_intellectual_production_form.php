<?= form_open("save_production") ?>
	<div class="header"></div>
		<div class="form-group">
			<?= form_label("Título da produção", "title") ?>
			<?= form_input(array(
				"name" => "title",
				"id" => "title",	
				"type" => "text",
				"class" => "form-control",
				"required" => "required"
			))?>
		</div>


		<div class="form-group">
			<?= form_label("Ano", "year") ?>
			<?= form_input(array(
				"name" => "year",
				"id" => "year",	
				"type" => "text",
				"class" => "form-control",
			))?>
		</div>

		<div class="form-group">
			<?= form_label("Tipo da produção", "type") ?>
			<?= form_dropdown("types", $types, "", ['class' => "form-control", 'id' => "types"]) ?>

		</div>

		<div class="form-group">
			<?= form_label("Subtipo da produção", "subtype") ?>
			<?= form_dropdown("subtypes", $subtypes, "", ['class' => "form-control", 'id' => "subtypes"]) ?>

		</div>
		
		<div class="form-group">
			<?= form_label("Título do periódico", "periodic") ?>
			<?= form_input(array(
				"name" => "periodic",
				"id" => "periodic",	
				"type" => "text",
				"class" => "form-control",
			))?>
		</div>

		<div class="form-group">
			<?= form_label("ISSN ou ISBN", "identifier") ?>
			<?= form_input(array(
				"name" => "identifier",
				"id" => "identifier",	
				"type" => "text",
				"class" => "form-control",
			))?>					
		</div>

		<div class="form-group">
			<?= form_label("Qualis", "qualis") ?>
			<?= form_input(array(
				"name" => "qualis",
				"id" => "qualis",	
				"type" => "text",
				"class" => "form-control",
				"readonly" => "readonly"
			))?>					
		</div>
		
		<div class="footer">
		<?= form_button(array(
		"id" => "new_expense_detail",
		"class" => "btn bg-olive btn-block",
		"type" => "submit",
		"content" => "Salvar"
		)) ?>
		</div>


<?= form_close() ?>

<br><br><br>