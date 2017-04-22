<?php

function createTimelineItemToAddDivulgation($processId){

	$method = 'addTimelineItem('.$processId.')';

	echo "<li>";
        echo "<a href='#' onclick={$method} class='fa fa-plus-square bg-blue'></a>";
        echo "<div id='new_divulgation' class='timeline-item'>";
        echo "</div>";
    echo "</li>";
}

function showDivulgations($selectiveprocess, $processDivulgations, $phasesName, $guestUser = FALSE){

	$processName = $selectiveprocess->getName();
	$processId = $selectiveprocess->getId();
	$courseId = $selectiveprocess->getCourse();


	if($processDivulgations){

		echo "<h4>Divulgações realizadas</h4>";
		echo "<ul class='timeline'>";

		writeTimelineLabel("blue", "Processo seletivo:".$processName);

		$label = "Processo Seletivo";
		foreach ($processDivulgations as $divulgation) {

			$phaseId = $divulgation['related_id_phase'];
			if(!is_null($phaseId)){
				$labelHasChanged = $label != $phasesName[$phaseId];
				if($labelHasChanged){
					$label = $phasesName[$phaseId];
					writeTimelineLabel("white", $label);
				}
			}
			else{
				$labelHasChanged = $label != "Divulgação";
				if($labelHasChanged){
					$label = "Divulgação";
					writeTimelineLabel("white", $label);
				}
			}
			$text = $divulgation['description'];
			$message = "";
			$message .= $divulgation['message'];

			$hasFile = !is_null($divulgation['file_path']);
			if($divulgation['initial_divulgation']){
	        	$link = site_url('download_notice/'.$processId.'/'.$courseId);
	        	$message .= "<br>Clique para baixar.";
			}
			elseif ($hasFile) {
	        	$link = site_url('selection_process/download_divulgation_file/'.$divulgation['id']);
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

	}
	else{
		$content = function(){
			echo "Processo seletivo sem divulgações.<br>";
        	echo "<p>Para adicionar uma divulgação (como editais, retificações, comunicados, entre outros) basta clicar no ícone <i class='fa fa-plus-square'></i>.</p>";
		};
		alert($content);
		echo "<br>";
		echo "<ul class='timeline'>";
		writeTimelineLabel("blue", "Processo seletivo:".$processName);
	}

	if(!$guestUser){
		createTimelineItemToAddDivulgation($processId);
	}
	echo "</ul>";
}

function defineDateForm($processId, $submitBtnId, $startDateFieldId, $endDateFieldId, $startDateValue = "", $endDateValue = ""){

	$hidden = array(
		'id' => "process_id",
		'name' => "process_id",
		'type' => "hidden",
		'value' => $processId
	);


	$submitBtn = array(
		"id" => $submitBtnId,
		"content" => "Definir",
		"class" => "btn bg-primary btn-flat",
		"type" => "submit"
	);

	$startDate = array(
	    "name" => $startDateFieldId,
	    "id" => $startDateFieldId,
	    "type" => "text",
		"placeholder" => "Informe a data inicial",
	    "class" => "form-campo",
	    "class" => "form-control",
	    "value" => $startDateValue
	);

	$endDate = array(
	    "name" => $endDateFieldId,
	    "id" => $endDateFieldId,
	    "type" => "text",
		"placeholder" => "Informe a data final",
	    "class" => "form-campo",
	    "class" => "form-control",
	    "value" => $endDateValue
	);
	echo form_input($hidden);

	echo "<form class='form-inline' role='form'>";

		echo form_input($startDate);
		echo " a ";
		echo form_input($endDate);

		echo form_button($submitBtn);
	echo "</form>";

}
