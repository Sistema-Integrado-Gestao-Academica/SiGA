<script src=<?=base_url("js/production.js")?>></script>

<h2 class="principal"><!-- <i class="fa fa-files-o"></i>  -->Suas produções</h2>

<br>
	
	<div class="row">
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

		<div align='right'>
			<i class='fa fa-eye'> Visualizar </i> &nbsp&nbsp
			<i class='fa fa-edit'> Editar </i> &nbsp&nbsp
			<i class='fa fa-remove'> Excluir </i>
		</div>
	</div>
	<hr>

    <?php if($intellectualProductions !== FALSE){ ?>
	<h3>Produções intelectuais</h3>

    <div class="box-body table-responsive no-padding">
		<table id="intellectual_production_table" class="table table-bordered table-hover tablesorter" >
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
    			$title = $production->getTitle();
    		?>

			<tr>

			<td>
				<?= $title?>
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
			 <a href=<?="#intellectual_production_".$id?> data-toggle="modal" class="btn btn-success">  <i class="fa fa-eye"></i> </a>

			<?= createIntellectualProductionModal($production) ?>

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

	if($eventPresentations !== FALSE){ ?>
		<h3>Apresentações de trabalhos</h3>
	
	<div class="box-body table-responsive no-padding">
		<table id="event_presentation_table" class="table table-bordered table-hover tablesorter" >
			<thead>
				<tr>
					<th class="text-center">Título do trabalho</th>
					<th class="text-center">Natureza da apresentação</th>
					<th class="text-center">Evento</th>
					<th class="text-center">Natureza do evento</th>
					<th class="text-center">Ações</th>
				</tr>
			</thead>

			<tbody>

    		<?php foreach ($eventPresentations as $presentation) { 
    			$id = $presentation['id'];
    		?>
			<tr>

			<td>
				<?= $presentation['study_title']?>
			</td>

			<td>
				<?= $presentation['presentation_nature']?>
			</td>

			<td>
				<?= $presentation['event_name']?>
			</td>

			<td>
				<?= $presentation['event_nature']?>
			</td>

			<td>
				
			<!-- Modal to see production-->
			 <a href=<?="#event_presentation_".$id?> data-toggle="modal" class="btn btn-success">  <i class="fa fa-eye"></i> </a>

			<?= createEventPresentationModal($presentation) ?>

			<?= anchor("edit_event_presentation/{$id}", "<i class='glyphicon glyphicon-edit'></i>", "class='btn btn-primary' style='margin-right:5%;'") ?>

			<button data-toggle="collapse" data-target=<?="#confirmation".$id?> class="btn btn-danger">
				<span class="fa fa-remove"></span>
			</button>

			<div id=<?="confirmation".$id?> class="collapse">
				<?= form_open("delete_event_presentation") ?>
				<?= form_hidden("id", $id) ?>
				<br>
				Deseja realmente remover essa apresentação?
				<br>
				<?= form_button(array(
						"id" => "delete_event_presentation_btn",
						"class" => "btn bg-danger",
						"content" => "Remover apresentação",
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

	if($eventParticipations !== FALSE){ ?>
		<h3>Participações em Eventos</h3>
	
	<div class="box-body table-responsive no-padding">
		<table id="event_presentation_table" class="table table-bordered table-hover tablesorter" >
			<thead>
				<tr>
					<th class="text-center">Evento</th>
					<th class="text-center">Natureza do evento</th>
					<th class="text-center">Ações</th>
				</tr>
			</thead>

			<tbody>

    		<?php foreach ($eventParticipations as $participation) { 
    			$id = $participation['id'];
    		?>
			<tr>
			<td>
				<?= $participation['event_name']?>
			</td>

			<td>
				<?= $participation['event_nature']?>
			</td>

			<td>
				
			<!-- Modal to see production-->
			 <a href=<?="#event_participation_".$id?> data-toggle="modal" class="btn btn-success">  <i class="fa fa-eye"></i> </a>

			<?= createEventParticipationModal($participation) ?>

			<?= anchor("edit_event_participation/{$id}", "<i class='glyphicon glyphicon-edit'></i>", "class='btn btn-primary' style='margin-right:5%;'") ?>

			<button data-toggle="collapse" data-target=<?="#confirmation".$id?> class="btn btn-danger">
				<span class="fa fa-remove"></span>
			</button>

			<div id=<?="confirmation".$id?> class="collapse">
				<?= form_open("delete_event_participation") ?>
				<?= form_hidden("id", $id) ?>
				<br>
				Deseja realmente remover essa apresentação?
				<br>
				<?= form_button(array(
						"id" => "delete_event_participation_btn",
						"class" => "btn bg-danger",
						"content" => "Remover apresentação",
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

	if(!$intellectualProductions && !$eventPresentations && !$eventParticipations){
		callout("info", "Nenhuma produção adicionada.");
	}


function createIntellectualProductionModal($production){

	$body = function() use ($production){
		include ("author_productions.php");	
	};

	$footer = function(){

	    echo "<button type='button' class='btn btn-danger' data-dismiss='modal'>Fechar</button>";
	};

	$id = $production->getId();
	$title = $production->getTitle();

	newModal("intellectual_production_{$id}", $title, $body, $footer);
}

function createEventPresentationModal($eventProduction){

	$body = function() use ($eventProduction){

		echo "<strong><h4> Dados do trabalho</h4></strong>";
		echo "<div class='box box-success'>";
		    echo "<div class='box-header with-border'>";
		    echo "</div>";
		    echo "<div class='box-body'>";
		      echo "<strong> Título</strong>";

		      echo "<p class='text'>";
		        echo $eventProduction['study_title'];
		      echo "</p>";
		      echo "<strong>Natureza</strong>";

		      echo "<p class='text'>";
		        echo $eventProduction['presentation_nature'];
		      echo "</p>";

		    echo "</div>";
		echo "</div>";

		include ("_event_info.php");	
	};

	$footer = function(){

	    echo "<button type='button' class='btn btn-danger' data-dismiss='modal'>Fechar</button>";
	};

	$id = $eventProduction['id'];
	$title = $eventProduction['study_title'];

	newModal("event_presentation_{$id}", "Apresentação do trabalho: {$title}", $body, $footer);
}

function createEventParticipationModal($eventProduction){

	$body = function() use ($eventProduction){
		include ("_event_info.php");	
	};

	$footer = function(){

	    echo "<button type='button' class='btn btn-danger' data-dismiss='modal'>Fechar</button>";
	};

	$id = $eventProduction['id'];
	$title = $eventProduction['event_name'];

	newModal("event_participation_{$id}", "Participação no evento: {$title}", $body, $footer);
}