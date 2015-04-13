
<br>
<br>

<h3>Solicitação de matrícula </h3>
<br>

<h3><span class='label label-primary'> Semestre atual: <?php echo $semester['description'];?> </span></h3>
<br>

<!-- In this case, the student did not requested enrollment -->
<?php if($requestDisciplinesClasses === FALSE){ ?>
	<div class="panel panel-primary">
		
		<div class="panel-heading">
			<h3 class="panel-title" align="center">Disciplinas adicionadas para solicitação</h3>
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

	<?php addDisciplinesToRequestForm($courseId, $userId, $semester['id_semester']); ?>

<!-- In this case, the student has requested enrollment -->
<?php }else{ ?>
	<div class="panel panel-success">
			
		<div class="panel-heading">
			<h3 class="panel-title" align="center">Solicitação de matrícula enviada</h3>
		</div>

		<div class="panel-body">
			<?php displaySentDisciplinesToEnrollmentRequest($requestDisciplinesClasses, $mastermind_message); ?>
		</div>
		
		<div class="panel-footer" align="left">
			<?php if($requestStatus !== FALSE){ ?>
			<h4>Status da solicitação: <b><i><?php echo $requestStatus?></b></i></h4>
			<?php } ?>
		</div>
	</div>
<?php } ?>

<br>
<br>
<?php echo anchor("usuario/studentCoursePage/{$courseId}/{$userId}", "Voltar", "class='btn btn-danger'"); ?>