<script src=<?=base_url("js/production.js")?>></script>

<h2 class="principal">Produções intelectuais</h2>

	<h4><a href="#form" data-toggle="collapse">  <i class="fa fa-plus-circle">Adicionar produção intelectual</i></a>	</h4>

    <div id="form" class="collapse">

		<div class="form-box-logged">
		
			<?= form_open("save_production") ?>
				<div class="header"></div>
				<div class="body bg-gray">

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
						))?>					
					</div>
					
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
		</div>
    </div>

