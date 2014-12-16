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
				<?=anchor("funcoes/{$budgetplan['id']}", "Vincular curso", array(
					"class" => "btn btn-primary btn-editar",
					"type" => "sumbit",
					"content" => "Vincular"
				))?>

				<?=anchor("funcoes/{$budgetplan['id']}", "Editar", array(
					"class" => "btn btn-primary btn-editar",
					"type" => "sumbit",
					"content" => "Editar"
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

<?php 
echo form_open("budgetplan/save");

echo form_label("Cadastrar um novo P.O.", "amount");
echo form_input(array(
	"name" => "amount",
	"id" => "amount",
	"type" => "number",
	"class" => "form-campo",
	"placeholder" => "Montante inicial"
));

echo form_label("Status", "status");
echo "<br>";
echo form_dropdown('status', $options);

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