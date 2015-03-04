
<br>
<br>

<h3>Solicitação de matrícula </h3>
<br>

<h3><span class='label label-primary'> Semestre atual: <?php echo $semester['description'];?> </span></h3>
<br>

<div class="panel panel-primary">
	
	<div class="panel-heading">
		<h3 class="panel-title">Disciplinas adicionadas para solicitação</h3>
	</div>

	<div class="panel-body">
	

	<div class="box-body table-responsive no-padding">
	<table class="table table-bordered table-hover">
		<tbody>
		   	<tr>
		       	<th class="text-center">Código</th>
		        <th class="text-center">Disciplina</th>
		       	<th class="text-center">Turma</th>
		    </tr>

		    <?php
		    	if($disciplinesToRequest !== FALSE){

		    	foreach($disciplinesToRequest as $discipline){
		    ?>

		    	<tr>
		    		<td></td>
		    		<td></td>
		    		<td></td>
		    		
		    	</tr>

		    <?php
		    	}
		    	}else{
		    ?>	
		    	<tr>
		    	<td colspan="3">
		    	<div class="callout callout-info">
		    		<h4>Nenhuma disciplina adicionada para solicitação de matrícula.</h4>
		    	</div>
		    	</td>
		    	</tr>
		    <?php
		    	}
		    ?>
		    
		</tbody>
	</table>
	</div>
	</div>

	<div class="panel-footer">
	</div>
</div>

<?php addDisciplinesToRequestForm($courseId, $userId); ?>

<?php echo anchor("usuario/studentCoursePage/{$courseId}/{$userId}", "Voltar", "class='btn btn-danger'"); ?>