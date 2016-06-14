<h2 class="principal">Naturezas das Despesas do Plano Orçamentário</h2>

<!-- <?= anchor("budgetplan/new_expense/{$budgetplan['id']}", "<i class='fa fa-plus-circle'></i> Adicionar despesa", "class='btn-lg'") ?> -->

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

					<button data-toggle="collapse" data-target=<?="#confirmation".$expense['id']?> class="btn btn-danger">
						<i class='fa fa-remove'> Remover </i>
					</button>
					
					<div id=<?="confirmation".$expense['id']?> class="collapse">
						<?= form_open("delete_expense_nature/{$expense['id']}") ?>
						<br>
						Deseja Realmente remover a natureza de despesa?
						<br>
						<?= form_button(array(
										"class" => "btn bg-danger btn-block",
										"type" => "submit",
										"content" => "Excluir",
									)) ?>
						<?= form_close() ?>
					</div>
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
