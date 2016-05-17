
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
				"student/temporaryrequest/confirmEnrollmentRequest/{$userId}/{$courseId}/{$semester['id_semester']}",
				"Confirmar solicitação",
				"class='btn btn-primary btn-flat'"
			);
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

		$searchBtn = array(
			"id" => "discipline_search_btn",
			"class" => "btn bg-olive btn-block",
			"content" => "Procurar disciplina",
			"type" => "submit",
			"style" => "width:80%"
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
		<div class='col-lg-6'>
			<div class='input-group input-group-sm'>
				<?= form_label("Nome da disciplina", "discipline_name_search");?>
				<?= form_input($disciplineSearch) ?>
				<?= form_input($courseHidden);?>
				<?= form_input($userHidden);?>
			</div>
		</div>
	</div>
	<br>
	<div class='row'>
		<div class='col-lg-3'>
			<?= form_button($searchBtn); ?>
		</div>
	</div>

	<br>
	<h4><i class='fa fa-list'> </i> Turmas disponíveis</h4>

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