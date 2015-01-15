<a href="<?=base_url("planoorcamentario/{$budgetplan['id']}/novadespesa")?>" class="btn-lg">
	<span class="glyphicon glyphicon-plus-sign">Adicionar</span>
</a>

<?php if ($expenses): ?>

	<table class="table table-striped table-bordered">
		<?php $i=0 ?>
		<tr>
			<td><h4 class="text-center">Despesas</h4></td>
			<td><h4 class="text-center">Ano</h4></td>
			<td><h4 class="text-center">Natureza da despesa</h4></td>
			<td><h4 class="text-center">Mês da liberação</h4></td>
			<td><h4 class="text-center">Valor</h4></td>
			<td></td>
		</tr>


	<?php foreach ($expenses as $expense): ?>
		<tr>
			<td><?=++$i?></td>
			<td><?=$expense['year']?></td>
			<td><?=$expense['nature']?></td>
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
			</td>
		</tr>
	<?php endforeach ?>
	</table>
<?php else: ?>
	<td><h3><label class="label label-default"> Sem despesas até o momento</label></h3></td>
<?php endif ?>