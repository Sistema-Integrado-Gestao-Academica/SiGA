<h2 class="principal">Detalhes da despesa <?= $expense['id']?></h2>
<div class="row">
<div class="col-lg-6">
<div class="box box-success">
    <div class="box-header with-border" data-widget="collapse">
      <i class="fa fa-minus"></i><h3 class="box-title">Sobre a despesa</h3> 
    </div>
    <!-- /.box-header -->
    <div class="box-body">

      <h4><strong><i class="fa fa-calendar"></i> Ano</strong></h4>

      <p class="text">
        <?=$expense['year']?>
      </p>

      <h4><strong> Mês da liberação</strong></h4>
			<p class="text">
        <?=$expense['month']?>
      </p>

      <hr>

      <h4><strong><i class="fa fa-dollar margin-r-5"></i> Valor</strong></h4>

      <p class="text"><?=currencyBR($expense['value'])?></p>

      <hr>

      <h4><strong><i class="fa fa-file-text-o margin-r-5"></i> Natureza da despesa</strong></h4>

      <p><?=$expense['expense_type_id']." - ".$expense['expense_type_description']?></p>
    </div>

    <!-- /.box-body -->
  </div>
</div>
<div class="col-lg-6">

	<?= anchor("new_expense_details/{$expense['id']}", "<i class='fa fa-plus-circle'></i> Adicionar despesa", "class='btn-lg'") ?>

	<h3>Despesas</h3>
	<div class="box-body table-responsive no-padding">
		<table id="expenses" class="table table-bordered table-hover tablesorter" >
			<thead>
				<tr>
					<th class="text-center">Nota de Empenho</th>
					<th class="text-center">Data de emissão </th>
					<th class="text-center">Nº Processo SEI</th>
					<th class="text-center">Valor</th>
				</tr>
			</thead>
			
			<tbody>
	
			<tr>
<!-- 				<td><?=$expense['year']?></td>
				<td><?=$expense['expense_type_id']." - ".$expense['expense_type_description']?></td>
				<td><?=$expense['month']?></td>
				<td><?=currencyBR($expense['value'])?></td> -->
			</tbody>
		</table>
	</div>
</div>
</div>