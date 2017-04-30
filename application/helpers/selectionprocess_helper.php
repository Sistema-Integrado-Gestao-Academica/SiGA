<?php
require_once(MODULESPATH."/program/constants/SelectionProcessConstants.php");

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


function defineDateTimeline($processId, $subscriptionStartDate, $subscriptionEndDate, $phases){

    // Subscription
    writeTimelineLabel("blue", "Inscrição");
    $text = "Período de inscrições";
    if($subscriptionStartDate != NULL && $subscriptionEndDate !== NULL){
        $text = "Período definido";
        $bodyText = function() use ($subscriptionStartDate, $subscriptionEndDate, $processId){
            echo "<b>Data de início:</b><br>";
            echo $subscriptionStartDate;
            echo "<b><br>Data de fim:</b><br>";
            echo $subscriptionEndDate;
            echo "<br><br>";
            echo "<b>Editar data definida</b>";
            defineDateForm($processId, 'define_subscription_date', "start_date", "end_date", $subscriptionStartDate, $subscriptionEndDate);
        };
    }
    else{
        $text = "Período de inscrições não definido";
        $bodyText = function() use ($processId){
            defineDateForm($processId, 'define_subscription_date', "start_date", "end_date");
        };
    }
    echo "<li>";
        echo "<i class='fa fa-calendar-o bg-blue'></i>";
        echo "<div class='timeline-item' id='subscription'>";
            writeTimelineItem($text, FALSE, "#", $bodyText);
        echo "</div>";
    echo "</li>";

    // Phases
    if($phases){
        foreach ($phases as $phase) {
            $phaseId = $phase->getPhaseId();
            $phaseName = $phase->getPhaseName();
            $labelId = "phase_label_".$phaseId;
            writeTimelineLabel("white", $phaseName, $labelId);
            $startDate = $phase->getStartDate();
            if(!is_null($startDate)){
                $text = "Período definido";
                $bodyText = function() use ($phase, $processId, $phaseId){
                    echo "<b>Data de início:</b><br>";
                    $startDate = $phase->getFormattedStartDate();
                    echo $startDate;
                    $endDate = $phase->getFormattedEndDate();
                    echo "<b><br>Data de fim:</b><br>";
                    echo $endDate;
                    echo "<hr>";
                    echo "<b>Editar data definida</b>";
                    defineDateForm($processId, 'define_date_phase_'.$phaseId, "phase_{$phaseId}_start_date", "phase_{$phaseId}_end_date", $startDate, $endDate);
                };
            }
            else{
                $text = "Período para a fase <b>{$phaseName}</b> não definido";
                $bodyText = function() use ($processId, $phaseId){
                    defineDateForm($processId, 'define_date_phase_'.$phaseId, "phase_{$phaseId}_start_date", "phase_{$phaseId}_end_date");
                };

            }
            echo "<li>";
                echo "<i class='fa fa-calendar-o bg-blue' id='phase_icon_{$phaseId}'></i>";
                echo "<div id='phase_{$phaseId}' class='timeline-item'>";
                    writeTimelineItem($text, FALSE, "#phase_{$phaseId}", $bodyText, "");
                echo "</div>";
            echo "</li>";

        }
    }
}

function inSubscriptionPeriod($process){
    $processStatus = getProcessStatusByDate($process);
    return $processStatus == SelectionProcessConstants::OPEN_FOR_SUBSCRIPTIONS;
}

function getProcessStatusByDate($process){

	$status = NULL;

	$noticePath = $process->getNoticePath();
	if(!is_null($noticePath)){
		$settings = $process->getSettings();

		$startDate = $settings->getStartDate();
		$today = new Datetime("America/Sao_Paulo");
		$today->setTime("0","0","0");
		$startDate->setTime("0","0","0");

		$beforeSubscription = validateDatesDiff($today, $startDate);
		$beforeSubscription = $today === $startDate ? !$beforeSubscription : $beforeSubscription;

		if($beforeSubscription){
			$status = SelectionProcessConstants::DISCLOSED;
		}
		else{
			$endDate = $settings->getEndDate();
			$endDate->setTime("0","0","0");
			$phases = $settings->getPhases();

			array_unshift($phases, $settings);

			$result = checkIfIsInSomePhase($today, $phases);

			if($result['isInPhase']){
				$status = $result['status'];
			}
			else{
				$lastPhase = array_pop($phases);
				$notFinished = validateDatesDiff($today, $lastPhase->getEndDate());
				if($notFinished){
					$status = $process->getStatus();
				}
				else{
					$status = SelectionProcessConstants::FINISHED;
				}

			}
		}
	}
	else{
		$status = SelectionProcessConstants::DRAFT;
	}


	return $status;
}

function checkIfIsInSomePhase($today, $phases){

	$phasesWithStatus = array(
		SelectionProcessConstants::HOMOLOGATION_PHASE => SelectionProcessConstants::IN_HOMOLOGATION_PHASE,
		SelectionProcessConstants::PRE_PROJECT_EVALUATION_PHASE => SelectionProcessConstants::IN_PRE_PROJECT_PHASE,
		SelectionProcessConstants::WRITTEN_TEST_PHASE => SelectionProcessConstants::IN_WRITTEN_TEST_PHASE,
		SelectionProcessConstants::ORAL_TEST_PHASE => SelectionProcessConstants::IN_ORAL_TEST_PHASE
	);

	$isInPhase = FALSE;
	$status = NULL;

	if($phases){
		foreach ($phases as $phase) {
			$startDate = $phase->getStartDate();

			$endDate = $phase->getEndDate();

			$isInPhase = validateDateInPeriod($today, $startDate, $endDate);

			if($isInPhase){
				if(method_exists($phase, 'getPhaseName')){
					$phaseName = $phase->getPhaseName();
					$status = $phasesWithStatus[$phaseName];
				}
				else{
					$status = SelectionProcessConstants::OPEN_FOR_SUBSCRIPTIONS;
				}
				break;
			}
		}
	}

	$data = array(
		'isInPhase' => $isInPhase,
		'status' => $status
	);

	return $data;
}

function getPhaseName($phaseId){

	$name = FALSE;
	switch ($phaseId) {
		case SelectionProcessConstants::HOMOLOGATION_PHASE_ID:
			$name = SelectionProcessConstants::HOMOLOGATION_PHASE;
			break;

		case SelectionProcessConstants::PRE_PROJECT_EVALUATION_PHASE_ID:
			$name = SelectionProcessConstants::PRE_PROJECT_EVALUATION_PHASE;
			break;

		case SelectionProcessConstants::WRITTEN_TEST_PHASE_ID:
			$name = SelectionProcessConstants::WRITTEN_TEST_PHASE;
			break;

		case SelectionProcessConstants::ORAL_TEST_PHASE_ID:
			$name = SelectionProcessConstants::ORAL_TEST_PHASE;
			break;
	}


	return $name;
}


function checkIfUserIsSecretary($course){
    // Check if the logged user is secretary of the course
	$ci =& get_instance();
	$ci->load->model('secretary/secretary_model');
    $userId = getSession()->getUserData()->getId();
    return $ci->secretary_model->isSecretaryOfCourse($userId, $course);
}