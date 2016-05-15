<h2 class="principal">Adicionar uma despesa</h2>

<div class="row">
<div class="col-lg-6">

<div class="form-box-logged" id="login-box">
	<div class="header" style="background-color: #3c8dbc">Adicionar despesa</div>

	<div class="body bg-gray">
		<?= form_open("save_expense") ?>
			<?= form_hidden("budgetplan_id", $budgetplan['id']) ?>
			<?= form_hidden("continue", "ok") ?>

			<div class="form-group">
				<?= form_label("Saldo disponível", "balance") ?>
				<?= form_input(array(
					"name" => "balance",
					"id" => "balance",
					"type" => "number",
					"class" => "form-campo",
					"value" => $budgetplan['balance'],
					"readonly" => "readonly"
				)) ?>
			</div>

			<div class="form-group">
				<?= form_label("Valor", "value") ?>
				<?= form_input(array(
					"name" => "value",
					"id" => "value",
					"type" => "number",
					"step" => 0.01, 
					"class" => "form-campo",
					"required" => "required"
				)) ?>
			</div>

			<div class="form-group">
				<?= form_label("Natureza da despesa", "nature") ?>
				<?= form_dropdown('type', $types) ?>
			</div>

			<div class="form-group">
				<?= form_label("Ano", "year") ?>
				<?= form_input(array(
					"name" => "year",
					"id" => "year",
					"type" => "number",
					"class" => "form-campo",
					"value" => getCurrentYear()
				)) ?>
			</div>

			<div class="form-group">
				<?= form_label("Mês da liberação", "month") ?><br>
				<?= form_dropdown('month', $months) ?>
			</div>

			<div class="footer body bg-gray">
				<div class="row">
					<div class="col-xs-6">
						<?= form_button(array(
							"class" => "btn bg-light-blue btn-block",
							"type" => "sumbit",
							"content" => "Salvar",
							"onclick" => "confirmation()"
						)) ?>
					</div>
					<div class="col-xs-6">
						<?= anchor("budgetplan/{$budgetplan['id']}", 'Voltar', "class='btn bg-light-blue btn-block'") ?>
					</div>
				</div>
			</div>
		<?= form_close() ?>
	</div>
</div>
</div>

</div>

<script>
	function confirmation() {
		var value = parseInt(document.getElementById("value").value);
		var balance = parseInt(document.getElementById("balance").value);

		if (value >= balance) {
			if (!confirm("Atenção! Este plano não tem saldo suficiente para esta despesa. Deseja continuar?")) {
				document.getElementsByName("continue")[0].value = "";
			}
		}
	}
</script>