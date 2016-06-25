<h2 class="principal">Detalhes da despesa <?= $expense['id']?></h2>

<?php

	$noteInput = array(
		"name" => "note",
		"id" => "note",
		"type" => "text",
		"class" => "form-control",
		"placeholder" => "Exemplo: 2011NE005787"
	);

	$dateInput = array(
		"name" => "expense_detail_emission_date",
		"id" => "expense_detail_emission_date",
		"type" => "text",
		"class" => "form-control"
	);

	$seiInput = array(
		"name" => "sei_process",
		"id" => "sei_process",
		"type" => "text",
		"class" => "form-control"	
	);

	$valueInput = array(
		"name" => "value",
		"id" => "value",
		"type" => "number",
		"step" => 0.01, 
		"class" => "form-control",
		"required" => "required"
	);

	$descriptionInput = array(
		"name" => "description",
		"id" => "description",
		"type" => "text",
		"class" => "form-control",
	);


	$id = $expense['id'];
?>

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

		<div class="form-box-logged">
		
			<?= form_open("save_expense_detail") ?>

		   	<?php include ('_form_expenses_expense.php');?>

			<?= form_close() ?>
		</div>
    </div>

	<?php if (!empty($expenses)){ ?>

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
						<th class="text-center">Descrição</th>
						<th class="text-center">Ações</th>
					</tr>
				</thead>
				
				<tbody>
		
				<?php foreach ($expenses as $expense_expenses) { 

					$date = $expense_expenses->getDMYEmissionDate();
					$id = $expense_expenses->getId();
					?>
				<tr>
	  				<td><?=$id?></td>

		  			<td>
		  			<?php if(!empty($expense_expenses->getNote())){

						echo $expense_expenses->getNote();
		  			}
		  			else{
		  				echo "-";
		  			}
		  			?>
		  			</td>

		  			<td>
		  			<?php if($date != ""){

						echo $date;
		  			}
		  			else{
	  					echo "-";
	  				}?>
		  			</td>
		  			<td>
		  			<?php 

	  				$sei_process = $expense_expenses->getSEIProcess();

	  				if(!empty($sei_process)){
						echo $sei_process;
	  				}
	  				else{
	  					echo "-";
	  				}?>
		  			<td>
		  			R$
	  				<?=$expense_expenses->getValue()?>
		  			</td>
		  			<td>
		  			<?php 

	  				$description = $expense_expenses->getDescription();

	  				if(!empty($description)){
						echo $description;
	  				}
	  				else{
	  					echo "-";
	  				}?>
		  			</td>
		  			<td>
		  			<?php echo anchor("edit_expense_detail/{$id}", "<i class='fa fa-pencil'> Editar </i>", "class='btn btn-primary'");?>
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