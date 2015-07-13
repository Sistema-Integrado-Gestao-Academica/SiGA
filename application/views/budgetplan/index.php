<h2 class="principal">Plano orçamentário</h2>

<table class="table table-striped table-bordered">
	<tr>
		<td><h3 class="text-center">P.O. cadastrados</h3></td>
	<?php if($budgetplans): ?>
		<td><h3 class="text-center">Curso</h3></td>
		<td><h3 class="text-center">Montante</h3></td>
		<td><h3 class="text-center">Gastos</h3></td>
		<td><h3 class="text-center">Saldo</h3></td>
		<td><h3 class="text-center">Status</h3></td>
		<td><h3 class="text-center">Ações</h3></td>
	</tr>

	<?php $i=0; ?>
	<?php foreach ($budgetplans as $budgetplan): ?>
		<tr>
			<td class="text-center"><?=++$i?></td>
			<td class="text-center"><?=$budgetplan['course']?></td>
			<td class="text-center"><?=currencyBR($budgetplan['amount'])?></td>
			<td class="text-center"><?=currencyBR($budgetplan['spending'])?></td>
			<td class="text-center"><?=currencyBR($budgetplan['balance'])?></td>
			<td class="text-center"><?=$budgetplan['status']?></td>

			<td>
				<?= anchor("budgetplan/budgetplanExpenses/{$budgetplan['id']}", "<i class='fa fa-dollar'></i>", "class='btn btn-warning btn-editar btn-sm' style='margin-right:2%;'") ?>
				<?= anchor("planoorcamentario/{$budgetplan['id']}", "<i class='fa fa-edit'></i>", "class='btn btn-primary btn-editar btn-sm' style='margin-right:10%;'") ?>
				<?= form_open('/budgetplan/delete') ?>
					<?= form_hidden('budgetplan_id', $budgetplan['id']) ?>
					<button type="submit" class="btn btn-danger btn-remover btn-sm" style="margin: -20px auto auto 100px;">
						<span class="glyphicon glyphicon-remove"></span>
					</button>
				<?= form_close() ?>
			</td>
		</tr>
	<?php endforeach ?>
	<?php else: ?>
		</tr>
		<tr>
			<td><h3><label class="label label-default"> Não existem planos orçamentários cadastrados</label></h3></td>
		</tr>
	<?php endif ?>
</table>

<div class="form-box-logged" id="login-box"> 
	<div class="header">Cadastrar um novo P.O.</div>

	<?= form_open("budgetplan/save") ?>
	<div class="body bg-gray">
		<div class="form-group">
			<?= form_label("Curso", "course") ?><br>
			<?= form_dropdown('course', $courses) ?>
		</div>

		<div class="form-group">
			<?= form_label('Montante inicial', 'amount') ?>
			<?= form_input(array(
				"name" => "amount",
				"id" => "amount",
				"type" => "number",
				"class" => "form-campo form-control",
				"required" => "required"
			)) ?>
		</div>

		<div class="form-group">	
			<?= form_label("Status", "status") ?><br>
			<?= form_dropdown('status', $status) ?>
		</div>

		<div class="footer body bg-gray">
			<?= form_button(array(
				"class" => "btn bg-olive btn-block",
				"type" => "sumbit",
				"content" => "Cadastrar"
			)) ?>
		</div>
	</div>
	<?= form_close() ?>
</div>

<script>
	$(document).ready(function() {
		$("#amount").inputmask("decimal", {
			radixPoint: ",",
			groupSeparator: ".",
			digits: 2,
			autoGroup: true,
			prefix: "R$"
		});
	});
</script>