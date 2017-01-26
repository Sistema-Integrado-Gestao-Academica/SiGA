<h2 class="principal">Editando Processo Seletivo <b><i><?php echo $selectiveprocess->getName();?></i></b> </h2>

<?php
	
$selectiveprocessId = $selectiveprocess->getId();
$settings = $selectiveprocess->getSettings();

$startDate = array(
    "name" => "selective_process_start_date",
    "id" => "selective_process_start_date",
    "type" => "text",
	"placeholder" => "Informe a data inicial",
    "class" => "form-campo",
    "class" => "form-control",
    "value" => htmlspecialchars($settings->getFormattedStartDate()) 
);

$endDate = array(
    "name" => "selective_process_end_date",
    "id" => "selective_process_end_date",
    "type" => "text",
	"placeholder" => "Informe a data final",
    "class" => "form-campo",
    "class" => "form-control",
    "value" => htmlspecialchars($settings->getFormattedEndDate()) 
);

$name = array(
	"name" => "selective_process_name",
	"id" => "selective_process_name",
	"type" => "text",
	"class" => "form-campo form-control",
	"placeholder" => "Informe o nome do edital",
	"maxlength" => "60",
	"value" => $selectiveprocess->getName()
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
	"id" => "edit_selective_process_btn",
	"class" => "btn bg-primary btn-flat",
	"content" => "Concluir Edição do Processo Seletivo"
);

$hidden = array(
	"id" => "course",
	"name" => "course",
	"type" => "hidden",
	"value" => $courseId
);

$selectiveprocessIdHidden = array(
	"id" => "processId",
	"name" => "processId",
	"type" => "hidden",
	"value" => $selectiveprocessId
);

$selectedStudentType = $selectiveprocess->getType();

include '_form.php';

?>
<?= form_input($selectiveprocessIdHidden); ?>
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
		if(!empty($phasesNames)){

			foreach($phasesNames as $id => $phase){

				// Homologation phase is obrigatory and do not have weight
				if($phase !== SelectionProcessConstants::HOMOLOGATION_PHASE){

						$selectName = "phase_".$id;
						$selectId = $selectName;
						$weight = $phasesWeights[$id];

						$phaseWeight["id"] = "phase_weight_".$id;
						$phaseWeight["name"] = "phase_weight_".$id;
						
						if($weight != -1){
							$selectedItem = TRUE;
							$phaseWeight["value"] = $weight;
						}
						else{
							$selectedItem = FALSE;
							$phaseWeight["value"] = "0";
						}

						$processPhases = array(
							TRUE => "Sim",
							FALSE => "Não",
						);

			?>
						<div class="row">

							<div class="col-md-10">
								<div class="input-group">
								<span class="input-group-addon">

									<?= form_label($phase, $selectName); ?>
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

							<?= form_label($phase); ?>
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
	

	<div id="phases_list_to_order_in_edition"></div>
	<br>
	<?php $noticePath = $selectiveprocess->getNoticePath();?> 
	<h4><i class="fa fa-cloud-upload" aria-hidden="true"></i> Edital enviado: <b><?= $noticeFileName ?></b></h4>
	<br>
	<div class="row">
		<div class="col-lg-3">
			<?php $url = site_url('download_notice/'.$selectiveprocessId.'/'.$courseId);?>
			<h4><a href=<?=$url?> class='btn btn-info'><i class='fa fa-cloud-download'></i>Baixar</a></h4>
		</div>

		<div class="col-lg-6">
		<?php
			if($divulgation !== FALSE){ 

				$divulgationDate = $divulgation['date'];
				$divulgationDate = convertDateTimeToDateBR($divulgationDate);
			    $today = new Datetime();
			    $today = $today->format("d/m/Y");

			    if($divulgationDate >= $today){ 
					echo "<h4><a href='#edit_notice_path' data-toggle='collapse' class='btn btn-info'><i class='fa fa-edit'>Enviar outro edital</i></a></h4>";
				}
				else{
					alert(function(){
		                echo "<h5>Não é possível editar o edital enviado, pois ele já foi divulgado. Para enviar outro arquivo faça uma divulgação.</h5>";
		            }, "info", FALSE, "info", $dismissible=TRUE);
				}
			}
			else{
				echo "<h4><a href='#edit_notice_path' data-toggle='collapse' class='btn btn-info'><i class='fa fa-edit'>Enviar outro edital</i></a></h4>";
			}

			?>
		</div>
	</div>

	<div id="edit_notice_path" class="collapse">

	<?php

	echo form_open_multipart("program/selectiveprocess/editNoticeFile", array( 'id' => 'edit_notice_path_form' ));

    echo form_input($hidden);
    echo form_input($selectiveprocessIdHidden);

    $noticeFile = array(
        "name" => "notice_file",
        "id" => "notice_file",
        "type" => "file"
    );

	$submitFileBtn = array(
        "id" => "edit_notice_path_btn",
        "class" => "btn btn-success btn-flat",
        "content" => "Salvar arquivo",
        "type" => "submit",
        "style" => "margin-top: 5%;"
    );
    
    include(MODULESPATH."/program/views/selection_process/_upload_notice_file.php");

	echo form_close();
	?>
	<div id="status_notice_file"></div>

	</div>
	<br>
	<br>
	<?= form_button($saveProcessBtn); ?>


	<div id="selection_process_saving_status"></div>

	<br>
	<br>


	<?= anchor(
		"program/selectiveprocess/courseSelectiveProcesses/{$courseId}",
		"Voltar",
		"class='btn btn-danger'"
	); ?>
