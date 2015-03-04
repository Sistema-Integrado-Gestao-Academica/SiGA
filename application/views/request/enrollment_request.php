
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
		<?php displayDisciplinesToRequest($disciplinesToRequest, $courseId, $userId, $semester['id_semester']); ?>
	</div>
	
	<div class="panel-footer" align="right">
	<?php
	if($thereIsDisciplinesToRequest){

		echo anchor(
			"temporaryrequest/confirmEnrollmentRequest/{$userId}/{$courseId}/{$semester['id_semester']}",
			"Confirmar solicitação",
			"class='btn btn-primary btn-flat'"
		);
	}
	?>
	</div>
</div>

<?php addDisciplinesToRequestForm($courseId, $userId); ?>

<br>
<br>
<?php echo anchor("usuario/studentCoursePage/{$courseId}/{$userId}", "Voltar", "class='btn btn-danger'"); ?>