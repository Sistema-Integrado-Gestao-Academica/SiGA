<div class="form-box"> 
	<div class="header">Nova despesa</div>
		<?= form_open("save_expense_detail") ?>
    	<div class="body bg-gray">

	<div class="form-group">
		<?= form_label("Nota de empenho", "note") ?>
		<?= form_input(array(
			"name" => "note",
			"id" => "note",
			"type" => "text",
			"class" => "form-control",
			"placeholder" => "Exemplo: 2011NE005787"
		)) ?>
	</div>

	<div class="form-group">
		<?= form_label("Data de Emissão", "expense_detail_emission_date") ?>
		<?= form_input(array(
			"name" => "expense_detail_emission_date",
			"id" => "expense_detail_emission_date",
			"type" => "text",
			"class" => "form-control"
		)) ?>
	</div>

	<div class="form-group">
		<?= form_label("Nº do Processo no SEI", "sei_process") ?>
		<?= form_input(array(
			"name" => "sei_process",
			"id" => "sei_process",
			"type" => "text",
			"class" => "form-control"
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
			"required" => "required"
		)) ?>
	</div>

	<div class="form-group">
		<?= form_label("Descrição", "description") ?>
		<?= form_textarea(array(
			"name" => "description",
			"id" => "description",
			"type" => "text",
			"class" => "form-control",
		)) ?>
	</div>

	<?= form_hidden("id", $expense['id']) ?>
</div>
<div class="footer">
<?= form_button(array(
	"id" => "new_expense_detail",
	"class" => "btn bg-olive btn-block",
	"type" => "submit",
	"content" => "Salvar"
)) ?>
<?= form_close() ?>
</div>
</div>