<h2 class="principal">Plano orçamentário</h2>

<table class="table table-striped table-bordered">
	<tr>
		<td><h3 class="text-center">P.O. cadastrados</h3></td>
		<?php if($budgetplans){ ?>
		<td><h3 class="text-center">Curso</h3></td>
		<td><h3 class="text-center">Montante</h3></td>
		<td><h3 class="text-center">Gastos</h3></td>
		<td><h3 class="text-center">Saldo</h3></td>
		<td><h3 class="text-center">Status</h3></td>
		<td><h3 class="text-center">Ações</h3></td>
	</tr>

	<?php $i=0; ?>
	<?php 
		foreach ($budgetplans as $budgetplan){ ?>
			<tr>
				<td class="text-center"><?=$i+=1?></td>
				<td class="text-center"><?=$budgetplan['course']?></td>
				<td class="text-center"><?=currencyBR($budgetplan['amount'])?></td>
				<td class="text-center"><?=currencyBR($budgetplan['spending'])?></td>
				<td class="text-center"><?=currencyBR($budgetplan['balance'])?></td>
				<td class="text-center"><?=$budgetplan['status']?></td>
	
				<td>
					<?=anchor("plano%20orcamentario/{$budgetplan['id']}", "Editar", array(
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
	<?php }
		}else{ ?>
		</tr>
		<tr>
			<td>
				<h3>
					<label class="label label-default"> Não existem planos orçmentários cadastrados</label>
				</h3>
			</td>
		</tr>
	<?php }?>
</table>

<div class="form-box-logged" id="login-box"> 
	<div class="header">Cadastrar um novo P.O.</div>
		<?= form_open("budgetplan/save") ?>
	<div class="body bg-gray">
		<div class="form-group">	
		<?php
		echo form_label('Montante inicial', 'amount');
		echo form_input(array(
			"name" => "amount",
			"id" => "amount",
			"type" => "number",
			"class" => "form-campo",
			"class" =>  "form-control"
		));
		?>
		</div>
		<div class="form-group">	
		<?php
		echo form_label("Status", "status");
		echo "<br>";
		echo form_dropdown('status', $status);
		?>
		</div>
		<div class="footer">	
		<?php	
		echo form_button(array(
			"class" => "btn bg-olive btn-block",
			"type" => "sumbit",
			"content" => "Cadastrar"
		));
		
		echo form_close();
		?>
		</div>
	</div>
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