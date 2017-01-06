<h2 class="principal">Definição de datas do Processo Seletivo <b><i><?=$selectiveprocess->getName()?></i></b> </h2>

<div id="divulgation_date_defined">

<?php 
    $divulgations = True; // Retirar depois essa linha
	$processDivulgation = FALSE;
	$processId = $selectiveprocess->getId();
	$settings = $selectiveprocess->getSettings();

	echo "<ul class='timeline'>";
        showDivulgationDateSection($settings, $processId, $processDivulgation);
        showSubscriptionSection($settings);
        showPhasesSection($settings, $processId);
    echo "</ul>";

?>

<?= anchor(
		"program/selectiveprocess/courseSelectiveProcesses/{$courseId}",
		"Voltar",
		"class='btn btn-danger'"
	); ?>