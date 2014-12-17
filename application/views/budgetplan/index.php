<h2 class="principal">Plano orçamentário</h2>

<table class="table table-striped table-bordered">
	<tr>
		<td><h3 class="text-center">P.O. cadastrados</h3></td>
		<td><h3 class="text-center">Curso</h3></td>
		<td><h3 class="text-center">Montante</h3></td>
		<td><h3 class="text-center">Gastos</h3></td>
		<td><h3 class="text-center">Saldo</h3></td>
		<td><h3 class="text-center">Status</h3></td>
		<td><h3 class="text-center">Ações</h3></td>
	</tr>

	<?php $i=0; ?>
	<?php foreach ($budgetplans as $budgetplan) { ?>
		<tr>
			<td class="text-center"><?=$i+=1?></td>
			<td class="text-center"><?=$budgetplan['course']?></td>
			<td class="text-center"><?=currencyBR($budgetplan['amount'])?></td>
			<td class="text-center"><?=currencyBR($budgetplan['spending'])?></td>
			<td class="text-center"><?=currencyBR($budgetplan['balance'])?></td>
			<td class="text-center"><?=$budgetplan['status']?></td>

			<td>
				<?=anchor("plano%20orcamentario/{$budgetplan['id']}", "Vincular curso", array(
					"class" => "btn btn-primary btn-editar",
					"type" => "sumbit",
					"content" => "Vincular"
				))?>
				
				<?php 
				echo form_open("budgetplan/remove");
				echo form_hidden("funcao_id", $budgetplan['id']);
				echo form_button(array(
					"class" => "btn btn-danger btn-remover",
					"type" => "sumbit",
					"content" => "Remover"
				));
				echo form_close();
				?>
			</td>
		</tr>
	<?php } ?>
</table>

<br><br>

<?= form_open("budgetplan/save") ?>

<h3>Cadastrar um novo P.O.</h3>

<?php
echo form_label('Montante inicial', 'amount');
echo form_input(array(
	"name" => "amount",
	"id" => "amount",
	"type" => "number",
	"class" => "form-campo"
));

echo form_label("Status", "status");
echo "<br>";
echo form_dropdown('status', $status);

echo "<br><br>";

echo form_button(array(
	"class" => "btn btn-primary",
	"type" => "sumbit",
	"content" => "Cadastrar"
));

echo form_close();
?>

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