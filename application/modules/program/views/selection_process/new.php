
<?php require_once (MODULESPATH."/program/constants/SelectionProcessConstants.php");  ?>

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

	$phaseWeight = array(
		"type" => "number",
		"min" => 0,
		"max" => 5,
		"steps" => 1,
		"class" => "form-control",
		"placeholder" => "Informe o peso dessa fase"
	);

	$saveProcessBtn = array(
		"id" => "open_selective_process_btn",
		"class" => "btn bg-primary btn-flat",
		"content" => "Abrir Processo Seletivo"
	);

	$hidden = array(
		"id" => "course",
		"name" => "course",
		"type" => "hidden",
		"value" => $course['id_course']
	);
?>

<!-- Basic data of selection process -->

<?= form_input($hidden); ?>

<h3><i class="fa fa-file-o"></i> Dados básicos</h3>
<br>

<div class="row">
	<div class="col-md-3">
		<?= form_label("Processo Seletivo para:", "student_type"); ?>
		<?= form_dropdown("student_type", $studentType, "","id='student_type'"); ?>
	</div>
	<div class="col-md-6">
		<?= form_label("Nome do edital", "selective_process_name"); ?>
		<?= form_input($name); ?>
	</div>
</div>

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
		Marque as fases desejadas como "Sim".<br>
		Ao lado do nome da fase, informe o peso da mesma.<br>
		Os pesos definidos são os pesos padrão.<br>
		Fique a vontade para alterar, lembrando que o peso máximo permitido é 5.
		</b></small></h4>
		
	<?php
		if(!empty($phases)){

			foreach($phases as $phase){
				
				// Homologation phase is obrigatory and do not have weight
				if($phase->getPhaseName() !== SelectionProcessConstants::HOMOLOGATION_PHASE){
				
				$selectName = "phase_".$phase->getPhaseId();
				$selectId = $selectName;
				$selectedItem = TRUE;

				$processPhases = array(
					TRUE => "Sim",
					FALSE => "Não",
				);
				
				$phaseWeight["id"] = "phase_weight_".$phase->getPhaseId();
				$phaseWeight["name"] = "phase_weight_".$phase->getPhaseId();
				$phaseWeight["value"] = $phase->getWeight();
	?>
				<div class="row">
					
					<div class="col-md-10">
						<div class="input-group">
						<span class="input-group-addon">
							
							<?= form_label($phase->getPhaseName(), $selectName); ?>
							<?= form_dropdown($selectName, $processPhases, $selectedItem, "id='{$selectId}'"); ?>
						</span>
						
						<?= form_input($phaseWeight); ?>
						</div>
					</div>
				</div>
				
			<?php   }else{ ?>

					<div class="row">
						<div class="col-md-10">
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

<h4><i class="fa fa-sort-amount-asc"></i> Ordem das fases do edital</h4>

<br>
Defina a ordem de execução das fases para este edital arrastando as fases para a posição desejada:
<br>

<div id="phases_list_to_order"></div>

<br>
<?= form_button($saveProcessBtn); ?>


<br>
<div id="selection_process_saving_status"></div>

<br>
<br>

<?= anchor(
		"program/selectiveprocess/courseSelectiveProcesses/{$course['id_program']}",
		"Voltar",
		"class='btn btn-danger'"
	);
?>
