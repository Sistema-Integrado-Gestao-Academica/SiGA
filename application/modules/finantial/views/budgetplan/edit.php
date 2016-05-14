<br>
<div class="row">
		<div class="form-box-logged" id="login-box">
			<div class="header">Alterar um P.O.</div>

			<div class="body bg-gray">
				<?= form_open("finantial/budgetplan/update") ?>
					<?= form_hidden("budgetplan_id", $budgetplan['id']) ?>
					<?= form_hidden("continue", "ok") ?>

					<div class="form-group">
						<?= form_label('Nome do P.O.', 'budgetplan_name') ?>
						<?= form_input(array(
							"name" => "budgetplan_name",
							"id" => "budgetplan_name",
							"type" => "text",
							"maxlength" => 20,
							"value" => $budgetplan['budgetplan_name'],
							"class" => "form-campo form-control"
						)) ?>
					</div>

					<div class="form-group">
						<?= form_label("Curso", "course") ?>
						<br>
						<?= form_dropdown('course', $courses, $budgetplan['course_id']) ?>
					</div>

					<?php
						if($managers !== FALSE){
							$managers[0] = "Nenhum";
						}else{
							$managers = array(0 => "Nenhum gestor cadastrado no sistema");
						}
					?>

					<div class="form-group">
						<?= form_label("Gestor", "manager") ?><br>
						<?= form_dropdown('manager', $managers, $budgetplan['manager']) ?>
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
						<?= form_label("Despesas", "spending") ?>
						<?= form_input(array(
							"name" => "spending",
							"id" => "spending",
							"type" => "number",
							"class" => "form-campo",
							"value" => $budgetplan['spending'],
							"readonly" => "readonly"
						)) ?>
					</div>

					<div class="form-group">
						<?= form_label("Saldo", "balance") ?>
						<?= form_input(array(
							"name" => "balance",
							"id" => "balance",
							"type" => "number",
							"class" => "form-campo",
							"value" => $budgetplan['balance'],
							"readonly" => "readonly"
						)) ?>
					</div>

				<?php if ($budgetplan['status'] != 4): ?>
					<div class="form-group">
						<?= form_label("Status", "status") ?>
						<br>
						<?= form_dropdown('status', $status, $budgetplan['status']-1, 'id="status"') ?>
					</div>

					<div class="footer body bg-gray">
						<div class="row">
							<div class="col-xs-6">
								<?= form_button(array(
									"class" => "btn bg-olive btn-block",
									"type" => "sumbit",
									"content" => "Salvar",
									"onclick" => "confirmation()"
								)) ?>
							</div>
							<div class="col-xs-6">
								<?= anchor('budgetplan', 'Voltar', "class='btn bg-olive btn-block'") ?>
							</div>
						</div>
				<?php else: ?>
						<div class="footer body bg-gray">
							<?= anchor('budgetplan', 'Voltar', "class='btn bg-olive btn-block'") ?>
						</div>
				<?php endif ?>
					</div>
				<?= form_close() ?>
			</div>
		</div>
		<br><br>
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