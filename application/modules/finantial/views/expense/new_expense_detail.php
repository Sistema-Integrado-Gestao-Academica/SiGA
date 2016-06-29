<h2 class="principal">Adicionar uma despesa</h2>

<div class="row">
<div class="col-lg-6">

<div class="form-box-logged">
	<div class="header">Adicionar despesa</div>

	<div class="body bg-gray">
		<?= form_open("save_expense_detail") ?>

			<div class="form-group">
				<?= form_label("Nota de empenho", "note") ?>
				<?= form_input(array(
					"name" => "note",
					"id" => "note",
					"type" => "text",
					"class" => "form-control",
					"placeholder" => "Exemplo: 2011NE005787",
					"required" => "required"
				)) ?>
			</div>

			<div class="form-group">
				<?= form_label("Data de Emissão", "expense_detail_emission_date") ?>
				<?= form_input(array(
					"name" => "emission_date",
					"id" => "expense_detail_emission_date",
					"type" => "text",
					"class" => "form-control",
				)) ?>
			</div>

			<div class="form-group">
				<?= form_label("Valor", "value") ?>
				<?= form_input(array(
					"name" => "value",
					"id" => "value",
					"type" => "number",
					"step" => 0.01, 
					"class" => "form-control",
				)) ?>
			</div>


			<div class="footer body bg-gray">
				<div class="row">
					<div class="col-xs-6">
						<?= form_button(array(
							"class" => "btn bg-olive btn-block",
							"type" => "sumbit",
							"content" => "Salvar",
						)) ?>
					</div>
				</div>
			</div>
		<?= form_close() ?>
	</div>
</div>
</div>

</div>