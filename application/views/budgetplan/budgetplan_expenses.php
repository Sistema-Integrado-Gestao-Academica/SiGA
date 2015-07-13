<h2 class="principal">Despesas do Plano Orçamentário</h2>

<?= anchor("planoorcamentario/{$budgetplan['id']}/novadespesa", "<i class='fa fa-plus-circle'></i> Adicionar despesa", "class='btn-lg'") ?>

<?php if ($expenses): ?>
	<br>
	<br>
	<div class="box-body table-responsive no-padding">
	<table class="table table-bordered table-hover">
		<?php $i=0; ?>
		<tbody>
			
		<tr>
			<th class="text-center">Despesa</th>
			<th class="text-center">Ano</th>
			<th class="text-center">Natureza da despesa</th>
			<th class="text-center">Mês da liberação</th>
			<th class="text-center">Valor</th>
			<th class="text-center">Ações</th>
		</tr>


	<?php foreach ($expenses as $expense): ?>
		<tr>
			<td><?=++$i?></td>
			<td><?=$expense['year']?></td>
			<td><?=$expense['expense_type']?></td>
			<td><?=$expense['month']?></td>
			<td><?=currencyBR($expense['value'])?></td>
			<td>	
				<?= form_open('expense/delete') ?>
					<?= form_hidden('expense_id', $expense['id']) ?>
					<?= form_hidden('budgetplan_id', $budgetplan['id']) ?>
					<button type="submit" class="btn btn-danger btn-xs">
						<span class="glyphicon glyphicon-remove"></span>
					</button>
				<?= form_close() ?>
				
				<?= anchor('payment/newPayment', "<i class='fa fa-dollar'></i>", "class='btn btn-primary'"); ?>

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
<?php echo anchor('planoorcamentario', 'Voltar', "class='btn btn-danger'");?>