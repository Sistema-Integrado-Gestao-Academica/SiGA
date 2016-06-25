<div class="header"></div>
<div class="body bg-gray">

	<div class="form-group">
		<?= form_label("Nota de empenho", "note") ?>
		<?= form_input($noteInput) ?>
	</div>

	<div class="form-group">
		<?= form_label("Data de Emissão", "expense_detail_emission_date") ?>
		<?= form_input($dateInput) ?>
	</div>

	<div class="form-group">
		<?= form_label("Nº do Processo no SEI", "sei_process") ?>
		<?= form_input($seiInput) ?>
	</div>

	<div class="form-group">
		<?= form_label("Valor", "value") ?>
		<?= form_input($valueInput) ?>
	</div>

	<div class="form-group">
		<?= form_label("Descrição", "description") ?>
		<?= form_textarea($descriptionInput) ?>
	</div>

	<?= form_hidden("id", $id) ?>
	</div>
	<div class="footer">
	<?= form_button(array(
	"id" => "new_expense_detail",
	"class" => "btn bg-olive btn-block",
	"type" => "submit",
	"content" => "Salvar"
	)) ?>
</div>
