<br>
<br>
<div id="selection_process_error_status"></div>

<?php

$processId = $process->getId();

$name = array(
	"name" => "selective_process_name",
	"id" => "selective_process_name",
	"type" => "text",
	"class" => "form-campo form-control",
	"placeholder" => "Informe o nome do edital",
	"maxlength" => "60",
	"value" => $process->getName(),
);

$vacancies = array(
	"id" => "total_vacancies",
	"name" => "total_vacancies",
	"type" => "number",
	"min" => 0,
	"steps" => 1,
	"class" => "form-control",
	"placeholder" => "Informe o número de vagas",
	"value" => $process->getVacancies()
);

$phaseWeight = array(
	"type" => "number",
	"min" => 0,
	"max" => 5,
	"steps" => 1,
	"class" => "form-control",
	"placeholder" => "Informe o peso dessa fase"
);

$phaseGrade = array(
	"type" => "number",
	"min" => 0,
	"max" => 100,
	"steps" => 1,
	"class" => "form-control",
	"placeholder" => "Informe a nota de corte",
);

$saveProcessBtn = array(
	"id" => "edit_selective_process_btn",
	"class" => "btn bg-primary btn-flat",
	"content" => "Concluir Edição do Processo Seletivo"
);

$hidden = array(
	"id" => "course",
	"name" => "course",
	"type" => "hidden",
	"value" => $process->getCourse()
);

$processHidden = array(
	"id" => "processId",
	"name" => "processId",
	"type" => "hidden",
	"value" => $processId
);


if($canNotEdit){
	$name["readonly"] = TRUE;
	$vacancies["readonly"] = TRUE;
	$phaseGrade["readonly"] = TRUE;
	$phaseWeight["readonly"] = TRUE;
}

$selectedStudentType = $process->getType();

include '_form.php';

$knockoutPhaseType = array(
	TRUE => 'Eliminatória',
	FALSE => 'Classificatória'
);

?>
<?= form_input($processHidden); ?>
<!-- Selection Process Settings -->
<h3><i class="fa fa-cogs"></i> Configurações do edital</h3>

<br>
<h4><i class="fa fa-files-o"></i> Fases do edital</h4>

<div class="row">
	<div class="col-md-8">
		<?= form_label("Selecione as fases que comporão o processo seletivo:"); ?>

		<h4><small><b>
		Marque as fases desejadas como "Sim".<br>
		Os pesos definidos são os pesos padrão.<br>
		Fique a vontade para alterar, lembrando que o peso máximo permitido é 5.<br>
		E a nota de corte máxima permitida é 100.
		</b></small></h4>

	<?php
		if(!empty($phases)){

			foreach($phases as $id => $phase){

				// Homologation phase is obrigatory and do not have weight
				if($id !== SelectionProcessConstants::HOMOLOGATION_PHASE_ID){
						$selectName = "phase_select_".$id;
						$selectId = $selectName;
						$weight = $phase['weight'];
						$grade = $phase['grade'];

						$phaseWeight["id"] = "phase_weight_".$id;
						$phaseWeight["name"] = "phase_weight_".$id;

						$gradeName = "phase_grade_".$id;
						$phaseGrade["id"] = $gradeName;
						$phaseGrade["name"] = $gradeName;
						$gradeDiv = $gradeName."_div";

						if($weight != -1){
							$selectedItem = TRUE;
							$phaseWeight["value"] = $weight;
							$phaseGrade["value"] = $grade;
						}
						else{
							$selectedItem = FALSE;
							$phaseWeight["value"] = "0";
							$phaseGrade["value"] = "0";
						}

						$processPhases = array(
							TRUE => "Sim",
							FALSE => "Não",
						);

						$selectPhaseName = "phase_type_".$id;
						$selectPhaseId = $selectPhaseName;
						$selectedType = $phase['knockoutPhase'];

						$class = $canNotEdit ? "disabled" : "";


						$fields = "phase_".$id."_fields";
			?>
						<div class="row">
							<div class="col-md-10">
								<div class="input-group">
									<span class="input-group-addon">
										<?= form_label($phase['name'], $selectName."_label"); ?>
									</span>
									<div class="input-group-btn">
										<?= form_dropdown($selectName, $processPhases, $selectedItem, "id='{$selectId}' class=form-control {$class}"); ?>
									</div>
								</div>
								<div id=<?=$fields?> style="display:none;">
									<div class="input-group">
										<span class="input-group-addon">
											<?= form_label("Tipo da fase", "phase_type"); ?>
										</span>
										<div class="input-group-btn">
											<?= form_dropdown($selectPhaseName, $knockoutPhaseType, $selectedType, "id='{$selectPhaseId}' class=form-control {$class}"); ?>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
												<?= form_label("Peso", $phaseWeight['name']); ?>
											<?= form_input($phaseWeight); ?>
										</div>
										<div class="col-md-6" id=<?=$gradeDiv?> style="display: none;">
												<?= form_label("Nota de Corte", $phaseGrade['name']); ?>
											<?= form_input($phaseGrade); ?>
										</div>
									</div>
								</div>
							</div>
						</div>
						<hr>

		<?php   }else{ ?>

				<div class="row">
					<div class="col-md-10">
						<div class="input-group">
						<span class="input-group-addon">

							<?= form_label($phase['name']); ?>
						<span class="label label-default">Fase obrigatória e sem peso.</span>
						</span>

						</div>
					</div>
				</div>

				<hr>

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


	<div id="phases_list_to_order_in_edition"></div>
	<br>

	<div class="col-sm-2 pull-right">
    	<button class='btn btn-primary' id="edit_selective_process_btn">Salvar e Continuar</button>
	</div>

