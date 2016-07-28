
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
			<?php displayDisciplinesToRequest($disciplinesToRequest, $courseId, $userId, $semester['id_semester']); ?>
		</div>

		<div class="panel-footer" align="right">
		<?php
		if($thereIsDisciplinesToRequest){

			echo anchor(
				"#confirm_request",
				"Confirmar solicitação",
				"class='btn btn-primary btn-flat' data-toggle='modal'"
			);
		?>
			<div id="confirm_request" class="modal fade">
			<div class="modal-dialog">
		    <div class="modal-content">
		        <div class="modal-header">
		            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		            <h2 class="modal-title text-center">
		            	Confirmar solicitação de matrícula
		            </h2>
		        </div>
		        <div class="modal-body text-center">
			        <div class="alert alert-info alert-dismissible" role="alert">
					  <i class="fa fa-question"></i>
			        	<h3><b>Deseja confirmar as disciplinas solicitadas?</b></h3>
			        	<br>
			        	<p align="left"> Disciplinas solicitadas:<br>
		        		<?php
		        			foreach ($disciplinesToRequest as $request){
		        				$class = $this->offer_model->getOfferDisciplineById($request['discipline_class']);
		        				$discipline = $this->discipline_model->getDisciplineByCode($class['id_discipline']);
		        				echo prettyDisciplineDescription($discipline, $class);
		        			}
		        		?>
			        	</p>
			        	<p>
			        		<br>Após confirmar a solicitação de matrícula <b>NÃO</b> é possível mais adicionar ou retirar disciplinas.
			        	</p>
					</div>
		        </div>
		        <div class="modal-footer">
		        	<div class='row'>
			        	<div class='col-md-6 col-sm-6'>
			        	<?= anchor(
								"student/temporaryrequest/confirmEnrollmentRequest/{$userId}/{$courseId}/{$semester['id_semester']}",
								"CONFIRMAR SOLICITAÇÃO",
								"id='confirm_enrollment_request_btn' class='btn btn-success btn-block btn-lg'"
							);
			        	?>
			        	</div>
			        	<div class='col-md-6 col-sm-6'>
			        	<button type="button" class="btn btn-danger btn-block btn-lg" data-dismiss="modal">CANCELAR</button>
			        	</div>
		        	</div>
		        </div>
		    </div>
			</div>
			</div>
		<?php
		}
		?>
		</div>
	</div>

	<?php
		$disciplineSearch = array(
			"name" => "discipline_name_search",
			"id" => "discipline_name_search",
			"type" => "text",
			"class" => "form-campo",
			"class" => "form-control"
		);

		$courseHidden = array(
			"id" => "courseId",
			"name" => "courseId",
			"type" => "hidden",
			"value" => $courseId
		);

		$userHidden = array(
			"id" => "userId",
			"name" => "userId",
			"type" => "hidden",
			"value" => $userId
		);
	?>

	<h3><i class='fa fa-search-plus'> </i> Adicionar disciplinas</h3>
	<br>

	<div class='row'>
		<div class='col-md-6 col-sm-6'>
			<div class='input-group input-group-sm'>
				<?= form_label("Nome da disciplina", "discipline_name_search");?>
				<?= form_input($disciplineSearch) ?>
				<?= form_input($courseHidden);?>
				<?= form_input($userHidden);?>
			</div>
		</div>
	</div>

	<br>
	<div id='discipline_search_result'>

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
			<h4>Status da solicitação: <b><i><?php echo $requestStatus?></b></i></h4>
			<?php } ?>
		</div>
	</div>
<?php } ?>

<br>
<br>
<?php echo anchor("student/student/studentCoursePage/{$courseId}/{$userId}", "Voltar", "class='btn btn-danger'"); ?>