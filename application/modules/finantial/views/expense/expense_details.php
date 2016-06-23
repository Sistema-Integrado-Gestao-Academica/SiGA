<h2 class="principal">Detalhes da despesa <?= $expense['id']?></h2>
<div class="row">

	<div class="col-lg-3">
	  <h4><strong><i class="fa fa-calendar"></i> Mês - Ano</strong></h4>

	  <p class="text">
	    <?=$expense['month']?>/
	    <?=$expense['year']?>
	  </p>
	</div>

	<div class="col-lg-3">
	  <h4><strong><i class="fa fa-dollar margin-r-5"></i> Valor</strong></h4>

	  <p class="text"><?=currencyBR($expense['value'])?></p>

	</div>
	  <h4><strong><i class="fa fa-file-text-o margin-r-5"></i> Natureza da despesa</strong></h4>

	  <p><?=$expense['expense_type_id']." - ".$expense['expense_type_description']?></p>
	</div>

 	<h4><a href="#form" data-toggle="collapse">  <i class="fa fa-plus-circle">Adicionar despesa</i></a>	</h4>

    <div id="form" class="collapse">
        <?php include ('_form_expenses_expense.php');?>
    </div>

	<?php if ($expenses !== FALSE){ ?>

		<h3>Despesas</h3>
		<div class="box-body table-responsive no-padding">
			<table id="expenses" class="table table-bordered table-hover tablesorter" >
				<thead>
					<tr>
						<th class="text-center">Despesa</th>
						<th class="text-center">Nota de Empenho</th>
						<th class="text-center">Data de emissão </th>
						<th class="text-center">Nº Processo SEI</th>
						<th class="text-center">Valor</th>
						<th class="text-center">Ações</th>
					</tr>
				</thead>
				
				<tbody>
		
				<?php foreach ($expenses as $expense_expenses) { 

					$date = $expense_expenses['emission_date'];
					?>
				<tr>
	  				<td><?=$expense_expenses['id']?></td>

		  			<td>
		  			<?php if(!empty($expense_expenses['note'])){

						echo $expense_expenses['note'];
		  			}
		  			else{
		  				echo "-";
		  			}
		  			?>
		  			</td>

		  			<td>
		  			<?php if($date != "0000-00-00"){

						echo $date;
		  			}
		  			else{
	  					echo "-";
	  				}?>
		  			</td>
		  			<td>
		  			<?php 

	  				$sei_process = $expense_expenses['sei_process'];

	  				if(!empty($sei_process)){
						echo $sei_process;
	  				}
	  				else{
	  					echo "-";
	  				}?>
		  			<td>
		  			R$
	  				<?=$expense_expenses['value']?>
		  			</td>

				</tr>
				<?php } ?>
				</tbody>
			</table>
		</div>
	<?php } 
	else{
		echo "<h4>Nenhuma despesa criada</h4>";
	} ?>

</div>