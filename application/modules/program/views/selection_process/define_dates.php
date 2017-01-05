<h2 class="principal">Definição de datas do Processo Seletivo <b><i><?=$selectiveprocess->getName()?></i></b> </h2>


<ul class="timeline">
	<?php 

		$settings = $selectiveprocess->getSettings();
		writeTimelineLabel("white", "Divulgação do Edital");
		$divulgations = True;
		if($divulgations){
			if($divulgations['initial_divulgation']){
				writeTimelineLabel("green", "Início:".$settings->getFormattedStartDate());
				writeTimelineLabel("red", "Fim:".$settings->getFormattedEndDate());
			}
			else{
				$text = "Data não definida";
				$bodyText = function(){ 
					echo "Você pode definir uma data ou divulgar o processo seletivo agora.";
				};
				$icon = "fa fa-calendar-o";
				$footer = function() use ($courseId){
					echo anchor("#", "Divulgar agora", "class='btn btn-success'");
					echo "&nbsp";
					echo "<button data-toggle='collapse' data-target=#define_date_form class='btn btn-primary'>Definir data</button>";
					echo "<br>";
					echo "<br>";
					echo "<div id='define_date_form' class='collapse'>";
					defineDateForm($courseId, 'define_divulgation_date', "divulgation_start_date", FALSE);
					echo "</div>";
				};
				writeTimelineItem($text, $icon, FALSE, "#", $bodyText, $footer);
			}
		}

		writeTimelineLabel("blue", "Inscrição");
		if($settings){
			writeTimelineLabel("green", "Início:".$settings->getFormattedStartDate());
			writeTimelineLabel("red", "Fim:".$settings->getFormattedEndDate());
		}

		$phases = $settings->getPhases();
		if($phases){
			foreach ($phases as $phase) {
				writeTimelineLabel("white", $phase->getPhaseName());
				$phaseId = $phase->getPhaseId();
				// $startDate = $phase->getFormmatedStartDate(); 
				// if(!is_null($startDate)){
				// 	writeTimelineLabel("green", "Início:".$startDate);
				// 	writeTimelineLabel("red", "Fim:".$phases->getFormattedEndDate());
				// }
				// else{

					$text = "Período não definido";
					$icon = "fa fa-calendar-o";
					$bodyText = function() use ($courseId, $phaseId){
						defineDateForm($courseId, 'define_date_phase_{$phaseId}', "phase_{$phaseId}_start_date", "phase_{$phaseId}_end_date");
					};
					writeTimelineItem($text, $icon, FALSE, "#", $bodyText, "");
				// }

			}
		}

		// $inputToStartDate = ;
		// $inputToEndDate = ;

	?>

</ul>

<?= anchor(
		"program/selectiveprocess/courseSelectiveProcesses/{$courseId}",
		"Voltar",
		"class='btn btn-danger'"
	); ?>