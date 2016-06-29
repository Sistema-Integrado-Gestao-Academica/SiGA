<?php require_once(MODULESPATH."/finantial/constants/ExpenseNatureConstants.php"); ?>

<h2 class="principal">Naturezas das Despesas do Plano Orçamentário</h2>

<?= anchor("new_expense_nature", "<i class='fa fa-plus-circle'></i> Adicionar natureza de despesa", "class='btn-lg'") ?>

<?php if ($expensesTypes): ?>
	<br>
	<br>
	<div class="box-body">
		<table class="table table-bordered table-hover" >
			<thead>
				<tr>
					<th class="text-center">Código </th>
					<th class="text-center">Natureza da despesa </i></th>
					<th class="text-center">Ações</th>
				</tr>
			</thead>
			
			<tbody>
	
		<?php foreach ($expensesTypes as $expense): ?>
			<tr>
				<td><?=$expense['code']?></td>
				<td><?=$expense['description']?></td>
				<td>
					<?php echo anchor("edit_expense_nature/{$expense['id']}", "<i class='fa fa-pencil'> Editar </i>", "class='btn btn-primary'");?>

					<?php 
					$status = $expense['status'];
					if($status != ExpenseNatureConstants::DEFAULT_STATUS) { 
						if($status == ExpenseNatureConstants::ACTIVE){
							$class = "btn bg-danger btn-block";
							echo "<button data-toggle='collapse' data-target=#confirmation{$expense['id']} class='btn btn-danger'>";
							$message = "Se você desativar essa natureza não poderá adicionar despesas para ela.";
						}
						else{
							$class = "btn bg-success btn-block";
							echo "<button data-toggle='collapse' data-target=#confirmation{$expense['id']} class='btn btn-success'>";
							$message = "";
						}
						?>
							<?= lang('toButton'.$status) ?>
						</button>
						
						<div id=<?="confirmation".$expense['id']?> class="collapse">
							<?= form_open("update_status_expense_nature/{$expense['id']}") ?>
							<br>
							<?= $message ?>
							<br>
							Deseja realmente <?= lang('toButton'.$status)?> a natureza de despesa?
							<br>
							<?= form_button(array(
											"class" => $class,
											"type" => "submit",
											"content" => lang('toButton'.$status),
										)) ?>
							<?= form_hidden('status', $status);?>
							<?= form_close() ?>
						</div>
					
					<?php 
					} 
					?>



				</td>
			</tr>
		<?php endforeach ?>
			</tbody>
		</table>
	</div>
<?php else: ?>
	<br>
	<br>
	<div class="callout callout-info">
		<h4>Sem naturezas de despesas até o momento.</h4>
	</div>
<?php endif ?>

<br>
<?php echo anchor('/', 'Voltar', "class='btn btn-danger'");?>
