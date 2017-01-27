<?php

function createDivulgationsModal($process, $divulgations){

	$processId = $process->getId();
	$settings = $process->getSettings();
	$phases = $settings->getPhases();

	$dropdownPhases = array('0' => "Nenhuma");
	if($phases !== FALSE){
		foreach ($phases as $phase) {
			$id = $phase->getPhaseId();
			$name = $phase->getPhaseName();
			$dropdownPhases[$id] = $name;
		}
	}

	$body = function() use ($processId, $dropdownPhases){
		formToAddDivulgation($processId, $dropdownPhases);
	};

	$footer = function(){
		echo "<div class='row'>";
			echo "<div class='col-lg-6'>";
				echo form_button(array(
				    "class" => "btn btn-danger btn-block",
				    "content" => "Fechar",
				    "type" => "button",
				    "data-dismiss"=>'modal'
				));
			echo "</div>";
			echo "<div class='col-lg-6'>";
			 	echo form_button(array(
				    "id" => 'divulgate',
				    "class" => "btn bg-olive btn-block",
				    "content" => 'Divulgar',
				    "type" => "submit"
				));
			echo "</div>";
		echo "</div>";

	};

	newModal("divulgationsmodal".$processId, "Divulgações do Processo Seletivo", $body, $footer);	
}

function formToAddDivulgation($processId, $phases, $descriptionValue = "", $messageValue = ""){
	$description = array(
		"name" => "description",
		"id" => "description",	
		"type" => "text",
		"required" => TRUE,
		"placeholder" => "Descrição da divulgação",
		"class" => "form-control",
		"required" => "true",
		"value" => $descriptionValue
	);	
	$message = array(
		"name" => "message",
		"id" => "message",	
		"type" => "text",
		"placeholder" => "Mensagem relacionada",
		"class" => "form-control",
		"value" => $messageValue
	);

	$processHidden = array(
		"id" => "process_id",
		"name" => "process_id",
		"type" => "hidden",
		"value" => $processId
	);

	echo "<h4><b> Nova divulgação </b></h4>";

	echo form_open_multipart("program/selectiveprocess/addDivulgationFile", array( 'id' => 'add_divulgation_form' ));

	echo form_label("Descrição", "description_label");
	echo form_input($description);
	echo "<br>";
	echo form_label("Mensagem", "message_label");
	echo form_textarea($message);
	echo form_label("Fase relacionada", "related_phase_label");
	echo form_dropdown("phase", $phases, '', "class='form-control'");
	echo form_input($processHidden);
	echo "<br>";

	$divulgationFile = array(
	    "name" => "divulgation_file",
	    "id" => "divulgation_file",
	    "type" => "file",
	    "required" => TRUE,
	    "class" => "filestyle",
	    "data-buttonBefore" => "true",
	    "data-buttonText" => "Procurar o arquivo",
	    "data-placeholder" => "Nenhum arquivo selecionado.",
	    "data-iconName" => "fa fa-file",
	    "data-buttonName" => "btn-primary",
	);
	echo "<br>";
	echo "<div class='row'>";
		echo "<div id='status_field_file'>";
		echo "</div>";
		echo form_label("Você pode incluir um arquivo para essa divulgação. <br><small><i>(Arquivos aceitos '.jpg, .png e .pdf')</i></small>:", "divulgation_file");
		echo "<div class='col-lg-8'>";
			echo form_input($divulgationFile); 
		echo "</div>";
	echo "</div>";
	echo form_close();

	echo "<div id='divulgate_result'>";
	echo "</div>";
}

function showDivulgations($selectiveprocess, $processDivulgations){

	if($processDivulgations){

	echo "<h4>Divulgações realizadas</h4>";
	$processName = $selectiveprocess->getName();
	$processId = $selectiveprocess->getId();
	$courseId = $selectiveprocess->getCourse();

	echo "<ul class='timeline'>";

		writeTimelineLabel("blue", "Processo seletivo:".$processName);

		foreach ($processDivulgations as $divulgation) {
			$text = $divulgation['description'];
			$message = "";
			$message .= $divulgation['message'];

			$hasFile = !is_null($divulgation['file_path']) || $divulgation['initial_divulgation'];
			if($hasFile){
	        	$link = site_url('download_notice/'.$processId.'/'.$courseId);
	        	$message .= "<br>Clique para baixar.";
			}
			else{
				$link = "#";
			}
	        $bodyText = function() use ($message){
	            echo $message;
	        };
	        $date = convertDateTimeToDateBR($divulgation['date']);
            $footer = "";
        	echo "<li>";
	            echo "<i class='fa fa-files-o bg-blue'></i>";
	            echo "<div id='divulgation' class='timeline-item'>";
	                writeTimelineItem($text, $date, $link, $bodyText, $footer);
	            echo "</div>";
	        echo "</li>";
		}

		echo "</ul>";
	}
	else{
		callout("info", "Processo seletivo sem divulgações");
	}



}