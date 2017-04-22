<br>
<br>
<?php
	$processId = $process->getId();
	$settings = $process->getSettings();
    $processName = $process->getName();
    $phases = $settings->getPhases();

	echo "<ul class='timeline' id='define_dates_timeline'>";
	    // Subscription
	    writeTimelineLabel("blue", "Inscrição");
	    if($settings){
	        $text = "Período de inscrições";
	        $startDate = $settings->getStartDate();
	        $endDate = $settings->getEndDate();
	        if($startDate != NULL && $endDate !== NULL){
	        	$formattedStartDate = $settings->getFormattedStartDate();
	        	$formattedEndDate = $settings->getFormattedEndDate();
                $text = "Período definido";
    	        $bodyText = function() use ($formattedStartDate, $formattedEndDate, $processId){
		            echo "<b>Data de início:</b><br>";
		            echo $formattedStartDate;
		            echo "<b><br>Data de fim:</b><br>";
		            echo $formattedEndDate;
		            echo "<br><br>";
	                echo "<b>Editar data definida</b>";
	                defineDateForm($processId, 'define_subscription_date', "start_date", "end_date", $formattedStartDate, $formattedEndDate);
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
	    }

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

    echo "</ul>";

    $idPhases = "\"{$phasesIds}\""; 
    $saveBtn = 'saveDefinedDates('.$processId.','.$idPhases.')';

?>

	<div class="col-sm-2 pull-left">
		<?= $backButton ?>
	</div>
	<div class="col-sm-2 pull-right" id="save_date">
    	<button class='btn btn-primary' onclick=<?=$saveBtn?> id="save_dates_btn">Salvar e Continuar</button>
	</div>

	<br>
	<br>

