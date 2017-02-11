<script src=<?=base_url("js/production.js")?>></script>

<h2 class="principal"><!-- <i class="fa fa-files-o"></i>  -->Suas produções</h2>

<br>

	<div class="dropdown">
	  <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
		<i class="fa fa-plus-square" aria-hidden="true"></i>
		Adicionar
	    <span class="caret"></span>
	  </button>
	  <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
		<li><a href="#addIntellectualProductionForm" data-toggle="collapse">Produção intelectual</a></li>
		<li><a href="#addEventPresentationForm" data-toggle="collapse">Apresentação de trabalho</a></li>
		<li><a href="#addEventParticipationForm" data-toggle="collapse">Participação em evento</a></li>
	  </ul>
	</div>
	<br>

	<?php include 'new_intellectual_production.php'; ?>
	<?php include 'new_event_participation.php'; ?>
	<?php include 'new_event_presentation.php'; ?>

	<hr>

    <?php if($intellectualProductions !== FALSE){ ?>
	<h3>Produções intelectuais</h3>

    <div class="box-body table-responsive no-padding">
		<table id="expenses" class="table table-bordered table-hover tablesorter" >
			<thead>
				<tr>
					<th class="text-center">Título da produção</th>
					<th class="text-center">Tipo</th>
					<th class="text-center">Subtipo</th>
					<th class="text-center">Ano</th>
					<th class="text-center">Ações</th>
				</tr>
			</thead>

			<tbody>

    		<?php foreach ($intellectualProductions as $production) { 
    			$id = $production->getId();
    		?>

			<tr>

			<td>
				<?= $production->getTitle()?>
			</td>

			<td>
				<?= $production->getTypeName()?>
			</td>

			<td>
				<?= $production->getSubtypeName()?>
			</td>

			<td>
				<?= $production->getYear()?>
			</td>

			<td>
				<!-- Modal to see production-->
				 <a href=<?="#myModal".$id?> data-toggle="modal" class="btn btn-success">  <i class="fa fa-search"></i> </a>
				 <!-- Modal HTML -->
				<div id=<?="myModal".$id?> class="modal fade">
				<div class="modal-dialog">
				    <div class="modal-content">
				        <div class="modal-header">
				            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				            <h4 class="modal-title">
				            	<?= $production->getTitle()?>
				            </h4>
				        </div>
				        <div class="modal-body">
				            <?php include ("author_productions.php");?>
				        </div>
				        <div class="modal-footer">
				            <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
				        </div>
				    </div>
				</div>
				</div>
				</div>
				<?= anchor("edit_production/{$id}", "<i class='glyphicon glyphicon-edit'></i>", "class='btn btn-primary' style='margin-right:5%;'") ?>

				<button data-toggle="collapse" data-target=<?="#confirmation".$id?> class="btn btn-danger">
					<span class="fa fa-remove"></span>
				</button>

				<div id=<?="confirmation".$id?> class="collapse">
					<?= form_open("delete_production") ?>
					<?= form_hidden("id", $id) ?>
					<br>
					Deseja realmente remover essa produção?
					<br>
					<?= form_button(array(
							"id" => "delete_production_btn",
							"class" => "btn bg-danger",
							"content" => "Remover produção",
							"type" => "submit"
						))?>
					<?= form_close() ?>
				</div>
			</td>

			</tr>

    		<?php } ?>

			</tbody>

		</table>
	</div>

	<?php } 