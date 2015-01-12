<h2 class="principal">Plano Orçamentário</h2>

<div class="form-box-logged" id="login-box">
	<div class="header">Alterar um P.O.</div>

	<?= form_open("budgetplan/update") ?>
	<?= form_hidden("budgetplan_id", $budgetplan['id']) ?>
	<?= form_hidden("continue", "ok") ?>

	<div class="body bg-gray">
		<div class="form-group">
			<?= form_label("Curso", "course") ?>
			<br>
			<?= form_dropdown('course', $courses, $budgetplan['course_id']-1) ?>
		</div>
		<div class="form-group">
			<?= form_label("Montante", "amount") ?>
			<?= form_input(array(
				"name" => "amount",
				"id" => "amount",
				"type" => "number",
				"class" => "form-campo",
				"value" => $budgetplan['amount'],
				$disable_amount => $disable_amount
			)) ?>
		</div>
		<div class="form-group">
			<?= form_label("Despesa", "spending") ?>
			<?= form_input(array(
				"name" => "spending",
				"id" => "spending",
				"type" => "number",
				"class" => "form-campo",
				"value" => $budgetplan['spending'],
				$disable_spending => $disable_spending
			)) ?>
		</div>
		<?php if ($budgetplan['status'] != 4): ?>
			<div class="form-group">
				<?= form_label("Status", "status") ?>
				<br>
				<?= form_dropdown('status', $status, $budgetplan['status']-1, 'id="status"') ?>
			</div>
			<div class="footer">
				<?= form_button(array(
					"class" => "btn bg-olive btn-block",
					"type" => "sumbit",
					"content" => "Salvar",
					"onclick" => "confirmation()"
				)) ?>
			</div>
		<?php else: ?>
			<div class="footer">
				<br><a href="<?=base_url('plano%20orcamentario')?>" class='btn bg-olive btn-block'>Voltar</a>
			</div>
		<?php endif ?>

		<?= form_close() ?>
	</div>
</div>

<script>
	function confirmation() {
		var status = document.getElementById("status").value;
		if (status == 2) {
			if (!confirm("ATENÇÃO! Com status \"Em execução\", não será mais possível alterar o valor do montante.")) {
				document.getElementsByName("continue")[0].value = "";
			}
		} else if (status == 3) {
			if (!confirm("ATENÇÃO! Com status \"Finalizado\", não será mais possível fazer alterações.")) {
				document.getElementsByName("continue")[0].value = "";
			}
		}
	}
</script>