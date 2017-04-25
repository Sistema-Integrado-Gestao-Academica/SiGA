<br>
<br>
<?php

	echo "<ul class='timeline' id='define_dates_timeline'>";
		$processId = $process->getId();
		$settings = $process->getSettings();
	    $phases = $settings->getPhases();
	    $startDate = $settings->getStartDate();
	    $endDate = $settings->getEndDate();

		$formattedStartDate = is_null($startDate) ? NULL: $settings->getFormattedStartDate();
		$formattedEndDate = is_null($endDate) ? NULL: $settings->getFormattedEndDate();
		defineDateTimeline($processId, $formattedStartDate, $formattedEndDate, $phases);
    echo "</ul>";

    $idPhases = "\"{$phasesIds}\""; 
    $saveBtn = 'saveDefinedDates('.$processId.','.$idPhases.')';

?>

	<div class="col-sm-2 pull-left">
		<?= $backButton ?>
	</div>
	<div class="col-sm-2 pull-right" id="save_date">
    	<button class='btn btn-primary' type="button" onclick=<?=$saveBtn?> id="save_dates_btn">Salvar e Continuar</button>
	</div>

	<br>
	<br>

