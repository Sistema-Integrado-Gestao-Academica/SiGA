<?php

function createTimelineItemToAddDivulgation($processId, $firstDivulgation = FALSE){
	
	if($firstDivulgation){
		$method = 'addFirstDivulgation('.$processId.')';
	}
	else{
		$method = 'addTimelineItem('.$processId.')';
	}

	echo "<li>";
        echo "<a href='#new_divulgation' onclick={$method} class='fa fa-plus-square bg-blue' data-container='body'
		             data-toggle='popover' data-placement='top' data-trigger='hover' disabled='true'
		             data-content='Clique para fazer uma nova divulgação'></a>";
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

		$firstDivulgation = FALSE;
	}
	else{
		callout("info", "Processo seletivo sem divulgações");
		echo "<br>";
		echo "<ul class='timeline'>";
		$firstDivulgation = TRUE;
		writeTimelineLabel("blue", "Processo seletivo:".$processName);
	}

	if(!$guestUser){
		createTimelineItemToAddDivulgation($processId, $firstDivulgation);
	}
	echo "</ul>";


}


function createDivulgationsModal($process){
	
	$processId = $process->getId();
    $fields = getFieldsOfDivulgationForm($process, TRUE);
    
    $fields['description']['value'] = "Edital ".$process->getName();
    $course = $process->getCourse();
    $body = function() use ($fields, $course){
    	echo form_input($fields['description']);
        echo form_textarea($fields['message']);
        echo form_input($fields['processHidden']);
        $courseHidden = array(
	        "id" => "course_id",
	        "name" => "course_id",
	        "type" => "hidden",
	        "value" => $course
	    );
        echo form_input($courseHidden);
    };
  
	$footer = function(){
		echo "<div id='divulgation_result'>";
		echo "</div>";
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

	newModal("divulgationsmodal".$processId, "Primeira divulgação do Processo Seletivo", $body, $footer);	
}

function getFieldsOfDivulgationForm($process, $initialDivulgation){
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

    $description = array(
        "name" => "description",
        "id" => "description",  
        "type" => "text",
        "required" => TRUE,
        "placeholder" => "Descrição da divulgação",
        "class" => "form-control",
        "required" => "true"
    );  
    $message = array(
        "name" => "message",
        "id" => "message",  
        "type" => "text",
        "placeholder" => "Mensagem relacionada",
        "class" => "form-control"
    );

    $processId = $process->getId();
    $processHidden = array(
        "id" => "process_id",
        "name" => "process_id",
        "type" => "hidden",
        "value" => $processId
    );

    $initialDivulgationHidden = array(
        "id" => "initial_divulgation",
        "name" => "initial_divulgation",
        "type" => "hidden",
        "value" => $initialDivulgation
    );

	$divulgationFile = array(
	    "name" => "divulgation_file",
	    "id" => "divulgation_file",
	    "type" => "file"
	);

	$fields = array(
		'description' => $description,
		'dropdownPhases' => $dropdownPhases,
		'message' => $message,
		'processHidden' => $processHidden,
		'initialDivulgationHidden' => $initialDivulgationHidden,
		'divulgationFile' => $divulgationFile
	);

	return $fields;
}