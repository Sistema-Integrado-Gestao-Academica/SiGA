<?php

function createTimelineItemToAddDivulgation($processId){
	
	echo "<li>";
        echo "<a href='#new_divulgation' onclick='addTimelineItem(\"{$processId}\")' class='fa fa-plus-square bg-blue' data-container='body'
		             data-toggle='popover' data-placement='top' data-trigger='hover' disabled='true'
		             data-content='Clique para fazer uma nova divulgação'></a>";
        echo "<div id='new_divulgation' class='timeline-item'>";
        echo "</div>";
    echo "</li>";
}

function showDivulgations($selectiveprocess, $processDivulgations, $phasesName){

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

	}
	else{
		callout("info", "Processo seletivo sem divulgações");
		echo "<br>";
		echo "<ul class='timeline'>";

		writeTimelineLabel("blue", "Processo seletivo:".$processName);
	}

	createTimelineItemToAddDivulgation($processId);
	echo "</ul>";


}


// function createDivulgationsModal($process, $divulgations){

// 	$processId = $process->getId();
// 	$settings = $process->getSettings();
// 	$phases = $settings->getPhases();

// 	$dropdownPhases = array('0' => "Nenhuma");
// 	if($phases !== FALSE){
// 		foreach ($phases as $phase) {
// 			$id = $phase->getPhaseId();
// 			$name = $phase->getPhaseName();
// 			$dropdownPhases[$id] = $name;
// 		}
// 	}

// 	$body = function() use ($processId, $dropdownPhases){
// 		formToAddDivulgation($processId, $dropdownPhases);
// 	};

// 	$footer = function(){
// 		echo "<div class='row'>";
// 			echo "<div class='col-lg-6'>";
// 				echo form_button(array(
// 				    "class" => "btn btn-danger btn-block",
// 				    "content" => "Fechar",
// 				    "type" => "button",
// 				    "data-dismiss"=>'modal'
// 				));
// 			echo "</div>";
// 			echo "<div class='col-lg-6'>";
// 			 	echo form_button(array(
// 				    "id" => 'divulgate',
// 				    "class" => "btn bg-olive btn-block",
// 				    "content" => 'Divulgar',
// 				    "type" => "submit"
// 				));
// 			echo "</div>";
// 		echo "</div>";

// 	};

// 	newModal("divulgationsmodal".$processId, "Divulgações do Processo Seletivo", $body, $footer);	
// }

