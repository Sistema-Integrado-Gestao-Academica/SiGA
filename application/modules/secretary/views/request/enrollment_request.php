
<h2 class="principal">Solicitação de matrícula </h2>

<h3><span class='label label-primary'> Semestre atual: <?php echo $semester['description'];?> </span></h3>
<br>

<!-- In this case, the student did not requested enrollment -->
<?php if($requestDisciplinesClasses === FALSE){ ?>
	<div class="panel panel-primary">

		<div class="panel-heading">
			<h3 class="panel-title" align="center">Disciplinas adicionadas para solicitação</h3>
		</div>

		<div class="panel-body">
			<?php
				displayDisciplinesToRequest($disciplinesToRequest, $courseId, $userId, $semester['id_semester']);
			?>
		</div>

		<div class="panel-footer" align="right">
		<?php
		if($thereIsDisciplinesToRequest){

			echo anchor(
				"student/temporaryrequest/confirmEnrollmentRequest/{$userId}/{$courseId}/{$semester['id_semester']}",
				"Confirmar solicitação",
				"id='confirm_enrollment_request_btn' class='btn btn-primary btn-flat'"
			);
		}
		?>
		</div>
	</div>

	<?php include('search_discipline_form.php'); ?>

<!-- In this case, the student has requested enrollment -->
<?php }else{ ?>
	<div class="panel panel-success">

		<div class="panel-heading">
			<h3 class="panel-title" align="center">Solicitação de matrícula enviada</h3>
		</div>

		<div class="panel-body">
			<?php displaySentDisciplinesToEnrollmentRequest($requestDisciplinesClasses); ?>

			<div class="callout callout-info">
				<h4>Mensagem do orientador:</h4>
				<p><b>
					<?php
						if($mastermindMessage !== FALSE){
							echo "\"".$mastermindMessage."\"";
						}else{
							echo "Sem mensagem no momento.";
						}
					?>
				</b><p>
			</div>
		</div>

		<div class="panel-footer" align="left">
			<?php if($requestStatus !== FALSE){ ?>
			<h4>Status da solicitação: <b><i><?php echo lang($requestStatus)?></b></i></h4>
			<br>

			<?php if($request['secretary_approval']){
				echo "<h4 class='text-center'>Solicitação já finalizada pela secretaria.<p><small>Solicitações finalizadas não podem ser editadas.</small></p></h4>";
			} ?>

			<?php
				// If there are disciplines refused, the student can request another ones
				$canUpdateRequest = ($requestStatus == EnrollmentConstants::REQUEST_PARTIALLY_APPROVED_STATUS
					|| $requestStatus == EnrollmentConstants::REQUEST_ALL_REFUSED_STATUS
					|| $requestStatus == EnrollmentConstants::REQUEST_INCOMPLETE_STATUS) && !$request['secretary_approval'];
				if($canUpdateRequest){
			?>
					<div class="alert alert-info alert-dismissible" role="alert">
				      <i class="fa fa-info"></i>
				      <h4 class="text-center">Que pena, você não conseguiu algumas disciplinas. Você pode solicitar outras disciplinas e alterar sua solicitação.<p><small> OBS.: Disciplinas já aprovadas não podem ser removidas.</small></p></h4>
				      <p align="center">
				      	<?= anchor("update_enroll_request/{$requestId}", "<i class='fa fa-exchange'></i> Alterar solicitação", "class='btn btn-default btn-flat btn-lg'")?>
				      </p>
				    </div>
			<?php
				}
			} ?>
		</div>
	</div>
<?php } ?>

<br>
<br>
<?php echo anchor("student/student/studentCoursePage/{$courseId}/{$userId}", "Voltar", "class='btn btn-danger'"); ?>