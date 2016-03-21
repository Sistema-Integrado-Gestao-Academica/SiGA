
<?php require_once (APPPATH."/constants/SelectionProcessConstants.php");  ?>

<h2 class="principal">Novo Processo Seletivo para o curso <b><i><?php echo $course['course_name'];?></i></b> </h2>

<?php
	
	$studentType = array(
		SelectionProcessConstants::REGULAR_STUDENT => 'Alunos Regulares',
		SelectionProcessConstants::SPECIAL_STUDENT => 'Alunos Especiais'
	);

	$startDate = array(
	    "name" => "selective_process_start_date",
	    "id" => "selective_process_start_date",
	    "type" => "text",
		"placeholder" => "Informe a data inicial",
	    "class" => "form-campo",
	    "class" => "form-control"
	);

	$endDate = array(
	    "name" => "selective_process_end_date",
	    "id" => "selective_process_end_date",
	    "type" => "text",
		"placeholder" => "Informe a data final",
	    "class" => "form-campo",
	    "class" => "form-control"
	);

	$name = array(
		"name" => "selective_process_name",
		"id" => "selective_process_name",
		"type" => "text",
		"class" => "form-campo form-control",
		"placeholder" => "Informe o nome do edital",
		"maxlength" => "60"
	);

	$noticeFile = array(
		"name" => "notice_file",
		"id" => "notice_file",
		"type" => "file"
	);

	$processPhases = array(
		"class" => "form-control",
		"checked" => TRUE
	);

	$phaseWeight = array(
		"type" => "number",
		"min" => 0,
		"max" => 5,
		"steps" => 1,
		"class" => "form-control",
		"placeholder" => "Informe o peso dessa fase"
	);

	$submitBtn = array(
		"id" => "open_selective_process_btn",
		"class" => "btn bg-primary btn-flat",
		"content" => "Abrir Processo Seletivo",
		"type" => "submit"
	);

	$hidden = array(
		"course" => $course['id_course']
	);
?>

<!-- Basic data of selection process -->

<h3><i class="fa fa-file-o"></i> Dados básicos</h3>
<br>

<?= form_open_multipart("selectiveprocess/newSelectionProcess"); ?>

<?= form_hidden($hidden); ?>

<div class="row">
	<div class="col-md-3">
		<?= form_label("Processo Seletivo para:", "student_type"); ?>
		<?= form_dropdown("student_type", $studentType, "id='student_type'"); ?>
	</div>
	<div class="col-md-6">
		<?= form_label("Nome do edital", "selective_process_name"); ?>
		<?= form_input($name); ?>
	</div>
</div>

<br>
<br>
<?= form_label("PDF do edital <small><i>(Arquivo PDF apenas)</i></small>:", "notice_file"); ?>
<?= form_input($noticeFile); ?>

<br>
<br>

<!-- Applying period of selection process -->

<h4><i class="fa fa-calendar"></i> Período de inscrições</h4>
<br>

<div class="row">
	<div class="col-md-3">
		<?= form_label("Data de início do edital", "selective_process_start_date"); ?>
		<?= form_input($startDate); ?>
	</div>
	<div class="col-md-3">
		<?= form_label("Data final do edital", "selective_process_end_date"); ?>
		<?= form_input($endDate); ?>
	</div>
</div>

<!-- Selection Process Settings -->
<br>
<br>
<h3><i class="fa fa-cogs"></i> Configurações do edital</h3>

<br>
<h4><i class="fa fa-files-o"></i> Fases do edital</h4>

<div class="row">
	<div class="col-md-8">
		<?= form_label("Selecione as fases que comporão o processo seletivo:"); ?>
		
		<h4><small><b>
		Marque as fases desejadas clicando em seus nomes.<br>
		Ao lado do nome da fase, informe o peso da mesma.<br>
		Os pesos definidos são os pesos padrão.<br>
		Fique a vontade para alterar, lembrando que o peso máximo permitido é 5.
		</b></small></h4>
		
	<?php
		if(!empty($phases)){

			foreach($phases as $phase){
				
				// Homologation phase is obrigatory and do not have weight
				if($phase->getPhaseName() !== SelectionProcessConstants::HOMOLOGATION_PHASE){

				$processPhases["id"] = "phase_".$phase->getPhaseId();
				$processPhases["name"] = "phase_".$phase->getPhaseId();
				$processPhases["value"] = $phase->getPhaseId();
				
				$phaseWeight["id"] = "phase_weight_".$phase->getPhaseId();
				$phaseWeight["name"] = "phase_weight_".$phase->getPhaseId();
				$phaseWeight["value"] = $phase->getWeight();
	?>
				<div class="row">
					
					<div class="col-md-8">
						<div class="input-group">
						<span class="input-group-addon">
						
							<?= form_checkbox($processPhases); ?>
							<?= form_label($phase->getPhaseName(), $processPhases["id"]); ?>
						</span>
						
						<?= form_input($phaseWeight); ?>
						</div>
					</div>
				</div>
				
			<?php   }else{ ?>

					<div class="row">
						<div class="col-md-8">
							<div class="input-group">
							<span class="input-group-addon">
							
								<?= form_label($phase->getPhaseName()); ?>
							<span class="label label-default">Fase obrigatória e sem peso.</span>
							</span>

							</div>
						</div>
					</div>

	<?php  			}
		    }
		}else{
			callout("info", "Não há fases cadastradas. Não é possível abrir o edital.");
			$submitBtn['disabled'] = TRUE;
		}
	?>

	</div>
</div>

<br>
<br>
<br>
  <style>
	  #sortable { margin: 0; padding: 0; width: 60%; }
	  #sortable li { margin: 0 3px 3px 3px; padding: 0.4em; padding-left: 1.5em; padding-bottom: 1.0em; font-size: 1.4em; height: 18px; }
	  #sortable li span { position: absolute; margin-left: -1.3em; }
  </style>

<div id="phases_order_list">
	<ol id = "sortable" style="cursor: move;">
		<li id="homologation"><span class="label label-primary">Homologação</span></li>
		<li id="pre_project"><span class="label label-primary">Avaliação de pré-projeto</span></li>
		<li id="written_test"><span class="label label-primary">Prova escrita</span></li>
		<li id="oral_test"><span class="label label-primary">Prova Oral</span></li>
	</ol>
</div>

<br>
<br>
<div class="row">
	<div class="col-md-3">
		<?= form_button($submitBtn) ?>
	</div>
</div>



<?= form_close(); ?>