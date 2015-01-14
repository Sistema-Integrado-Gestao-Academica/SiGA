<a href="<?=base_url("planoorcamentario/{$budgetplan['id']}/novadespesa")?>" class="btn-lg">
	<span class="glyphicon glyphicon-plus-sign">Adicionar</span>
</a>

<table class="table table-striped table-bordered">
	<?php $i=0 ?>
	<tr>
		<td><h4 class="text-center">Despesas</h4></td>
		<td><h4 class="text-center">Ano</h4></td>
		<td><h4 class="text-center">Natureza da despesa</h4></td>
		<td><h4 class="text-center">Mês da liberação</h4></td>
		<td><h4 class="text-center">Valor</h4></td>
		<td><h4 class="text-center">Ações</h4></td>
	</tr>


<?php foreach ($expenses as $expense): ?>
	<tr>
		<td><?=++$i?></td>
		<td><?=$expense['year']?></td>
		<td><?=$expense['nature']?></td>
		<td><?=$expense['month']?></td>
		<td><?=currencyBR($expense['value'])?></td>
		<td>
			<button type="button" class="btn btn-primary btn-sm">
				<span class="glyphicon glyphicon-edit"></span>
			</button>
			<button type="button" class="btn btn-danger btn-sm">
				<span class="glyphicon glyphicon-remove"></span>
			</button>
		</td>
	</tr>
<?php endforeach ?>
</table>
