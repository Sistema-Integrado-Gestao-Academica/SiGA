<h2 class="principal">Despesas do Plano Orçamentário</h2>

<?= anchor("budgetplan/new_expense/{$budgetplan['id']}", "<i class='fa fa-plus-circle'></i> Adicionar despesa", "class='btn-lg'") ?>

<?php if ($expenses): ?>
	<br>
	<br>
	<div class="box-body table-responsive no-padding">
		<table id="expenses" class="table table-bordered table-hover tablesorter" >
			<thead>
				<tr>
					<th class="text-center">Despesa <i class="fa fa-sort"></i></th>
					<th class="text-center">Ano <i class="fa fa-sort"></i></th>
					<th class="text-center">Natureza da despesa <i class="fa fa-sort"></i></th>
					<th class="text-center">Mês da liberação</th>
					<th class="text-center">Valor</th>
					<th class="text-center">Ações</th>
				</tr>
			</thead>
			
			<?php $i=0; ?>
			<tbody>
	
		<?php foreach ($expenses as $expense): ?>
			<tr>
				<td><?=++$i?></td>
				<td><?=$expense['year']?></td>
				<td><?=$expense['expense_type_id']." - ".$expense['expense_type_description']?></td>
				<td><?=$expense['month']?></td>
				<td><?=currencyBR($expense['value'])?></td>
				<td>
					
					<?= form_open('delete_expense') ?>
						<?= form_hidden('expense_id', $expense['id']) ?>
						<?= form_hidden('budgetplan_id', $budgetplan['id']) ?>
						<button type="submit" class="btn btn-danger btn-sm">
							<i class="fa fa-remove"> Remover despesa</i>
						</button>
					<?= form_close() ?>
	
					<?php
						
						$expenseHasPayment = $expense['expense_type_id'] == "339036" || $expense['expense_type_id'] == "339039";
	
						if($expenseHasPayment){
							echo anchor(
								"expense_payments/{$expense['id']}/{$budgetplan['id']}",
								"<i class='fa fa-dollar'> Pagamentos</i>",
								"class='btn btn-primary btn-sm' style='margin-top:5%;'"
							);
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
		<h4>Sem despesas até o momento.</h4>
	</div>
<?php endif ?>

<br>
<?php echo anchor('budgetplan', 'Voltar', "class='btn btn-danger'");?>

<script>
$(document).ready(function(){
	$(function(){
		$("#expenses").tablesorter();
	});
});
</script>