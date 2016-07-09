<script src=<?=base_url("js/production.js")?>></script>

<h2 class="principal"><!-- <i class="fa fa-files-o"></i>  -->Suas produções intelectuais</h2>

	<h4><a href="#form" data-toggle="collapse">  <i class="fa fa-plus-circle">Adicionar produção intelectual</i></a>	</h4>

	<?php include 'new_intellectual_production.php'; ?>
    
    <?php if($productions !== FALSE){ ?>



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

    		<?php foreach ($productions as $production) { 

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
				<?= anchor("edit_production/{$id}", "<i class='glyphicon glyphicon-edit'> Editar</i>", "class='btn btn-primary' style='margin-right:5%;'") ?>
			</td>

			</tr>
    			
    		<?php } ?>

			</tbody>

		</table>
	</div>	

	<?php } ?>
