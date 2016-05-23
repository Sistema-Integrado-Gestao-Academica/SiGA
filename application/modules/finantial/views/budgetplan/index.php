<h2 class="principal">Plano orçamentário</h2>

<div class="box-body table-responsive no-padding">
<table class="table table-bordered table-hover">
<tbody>
		
	<tr>
		<th class="text-center">P.O. cadastrados</th>
	<?php if($budgetplans): ?>
		<th class="text-center">Nome do P.O.</th>
		<th class="text-center">Gestor</th>
		<th class="text-center">Curso</th>
		<th class="text-center">Montante</th>
		<th class="text-center">Gastos</th>
		<th class="text-center">Saldo</th>
		<th class="text-center">Status</th>
		<th class="text-center">Ações</th>
	</tr>

	<?php $i=0; ?>
	<?php foreach ($budgetplans as $budgetplan): ?>
		<tr>
			<?php 

				if($budgetplan['budgetplan_name'] !== NULL){
					$budgetplanName = $budgetplan['budgetplan_name'];
				}else{
					$budgetplanName = "-";
				}

			    $foundUser = $userController->getUserById($budgetplan['manager']);
			    $userName = $foundUser['name'];
			?>
			<td class="text-center"><?=++$i?></td>
			<td class="text-center"><?=$budgetplanName?></td>
			<td class="text-center"><?=$userName?></td>
			<td class="text-center"><?=$budgetplan['course']?></td>
			<td class="text-center"><?=currencyBR($budgetplan['amount'])?></td>
			<td class="text-center"><?=currencyBR($budgetplan['spending'])?></td>
			<td class="text-center"><?=currencyBR($budgetplan['balance'])?></td>
			<td class="text-center"><?=$budgetplan['status']?></td>

			<td>
				<?= anchor("budgetplan_expenses/{$budgetplan['id']}", "<i class='fa fa-dollar'></i>", "class='btn btn-warning btn-editar btn-sm' style='margin-right:2%;'") ?>
				<?= anchor("budgetplan/{$budgetplan['id']}", "<i class='fa fa-edit'></i>", "class='btn btn-primary btn-editar btn-sm' style='margin-right:10%;'") ?>
				
				<button data-toggle="collapse" data-target=<?="#confirmation".$budgetplan['id']?> class="btn btn-danger btn-remover btn-sm" style='margin: -20px auto auto 100px;'>
					<span class=" glyphicon glyphicon-remove"></span>
				</button>
				
				<div id=<?="confirmation".$budgetplan['id']?> class="collapse">
					<?= form_open('finantial/budgetplan/delete') ?>
					<?= form_hidden('budgetplan_id', $budgetplan['id']) ?>
					<br>
					Deseja Realmente excluir o Plano Orçamentário?
					<br>
					<?= form_button(array(
									"class" => "btn bg-danger btn-block",
									"type" => "sumbit",
									"content" => "Excluir",
									"onclick" => "confirmation()"
								)) ?>
					<?= form_close() ?>
				</div>
			</td>
		</tr>
	<?php endforeach ?>
	<?php else: ?>
		</tr>
		<tr>
			<td><h3><label class="label label-default"> Não existem planos orçamentários cadastrados</label></h3></td>
		</tr>
	<?php endif ?>
</tbody>
</table>
</div>
<?php

$submitBtn = array(
		"class" => "btn bg-olive btn-block",
		"type" => "sumbit",
		"content" => "Cadastrar"
);

if($courses !== FALSE && $managers !== FALSE){
	$isPossibleRegisterPO = TRUE;
}
else{
	$isPossibleRegisterPO = FALSE;
	$submitBtn['disabled'] = TRUE;
}?>
<div class="form-box-logged" id="login-box"> 
	<div class="header">Cadastrar um novo P.O.</div>

	<?= form_open("finantial/budgetplan/save") ?>
	<div class="body bg-gray">

		<div class="form-group">
			<?= form_label('Nome do P.O.', 'budgetplan_name') ?>
			<?= form_input(array(
				"name" => "budgetplan_name",
				"id" => "budgetplan_name",
				"type" => "text",
				"maxlength" => 20,
				"class" => "form-campo form-control"
			)) ?>
		</div>
		
		<?php
			if($courses == FALSE){
				$courses = array(0 => "Nenhum curso cadastrado no sistema");
			}
		?>

		<div class="form-group">
			<?= form_label("Curso", "course") ?><br>
			<?= form_dropdown('course', $courses) ?>
		</div>

		<?php
			if($managers == FALSE){
				$managers = array(0 => "Nenhum gestor cadastrado no sistema");
			}
		?>

		<div class="form-group">
			<?= form_label("Gestor", "manager") ?><br>
			<?= form_dropdown('manager', $managers) ?>
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
			<?= form_button($submitBtn) ?>
		</div>
	</div>
	<?= form_close() ?>

	<?php if(!$isPossibleRegisterPO){ ?>
		<div class="callout callout-danger">
			<h4>Não é possível cadastrar um P.O. sem um curso e um gestor.</h4>
		</div>
	<?php } ?>
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