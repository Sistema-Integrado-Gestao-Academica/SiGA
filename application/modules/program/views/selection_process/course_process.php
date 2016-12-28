
<h2 class="principal">Processos Seletivos do curso <b><i><?=$course[Course_model::COURSE_NAME_ATTR]?></i></b> </h2>

<?php
require_once(MODULESPATH."/program/constants/SelectionProcessConstants.php");

echo anchor(
	"program/selectiveprocess/openSelectiveProcess/{$course[Course_model::ID_ATTR]}",
	"<i class='fa fa-plus-square'></i> Abrir edital para <b>".$course[Course_model::COURSE_NAME_ATTR]."</b>",
	"class = 'btn btn-lg'"
);

buildTableDeclaration();

buildTableHeaders(array(
	'Edital',
	'Tipo',
	'Status',
	'Ações'
));

$validSelectiveProcesses = !empty($selectiveProcesses) && !is_null($selectiveProcesses);
if($validSelectiveProcesses){
	foreach($selectiveProcesses as $process){
		$processName = $process->getName();
		$settings = $process->getSettings();
		echo "<tr>";

			echo "<td>";
				echo "<h4><span class='label label-primary'>".$processName."</span></h4>";
			echo "</td>";

			echo "<td>";
				echo $process->getFormmatedType();
			echo "</td>";

			echo "<td>";
				labelToStatus($settings);
			echo "</td>";

			echo "<td>";
				$processId = $process->getId();
				$body = function() use ($process, $settings){
					echo "<div align='left'>";
					echo "<b>Tipo:</b> ".$process->getFormmatedType();
					echo "<br>";
					echo "<b>Data de Início: </b>".$settings->getFormattedStartDate();
					echo "<br>";
					echo "<b>Data de Fim: </b>".$settings->getFormattedEndDate();
					echo "<br>";
					echo "</div>";
					$phasesOrder = $settings->getPhasesOrder();
					$phases = $settings->getPhases();
					$validPhases = !empty($phases) && !is_null($phases);
					if($validPhases){
						echo "<h4><b>Fases:</b><br></h4>";
						buildTableDeclaration();

						buildTableHeaders(array(
							'Ordem',
							'Fase',
							'Peso'
						));

						foreach ($phases as $phase) {
							$phaseName = $phase->getPhaseName();
							$phaseId = $phase->getPhaseId();
							echo "<tr>";
								echo "<td>";
								if($phaseId != 1){
									$phaseName = lang($phaseName);
									$order = array_search($phaseName, $phasesOrder);
									echo $order + 1;
								}
								else{
									echo "-";
								}
								echo "</td>";
								echo "<td>";
									echo $phase->getPhaseName();
								echo "</td>";
								echo "<td>";
									if($phaseId != 1){
										echo $phase->getWeight();
									}
									else{
										echo "0";
									}
								echo "</td>";
							echo "</tr>";
						}
						buildTableEndDeclaration();
					}

				};

				$footer = function(){
					echo form_button(array(
					    "class" => "btn btn-danger btn-block",
					    "content" => "Fechar",
					    "type" => "button",
					    "data-dismiss"=>'modal'
					));
				};

				newModal("selectiveprocessmodal".$processId, "Processo Seletivo: <b>{$processName}</b>", $body, $footer);
				
				echo "<a href='#selectiveprocessmodal{$processId}' data-toggle='modal' class='btn btn-success'>Visualizar</a>";
				echo anchor("program/selectiveprocess/edit/{$processId}/{$course[Course_model::ID_ATTR]}", "Editar", "class='btn btn-primary'");

			echo "</td>";

		echo "</tr>";
	}

}else{
	echo "<tr>";
		echo "<td colspan=4>";
			callout("info", "Não existem processos seletivos abertos para este curso.");
		echo "</td>";
	echo "</tr>";
}

buildTableEndDeclaration();

echo "<br>";

echo anchor("program/selectiveprocess/programCourses/{$course['id_program']}", "Voltar", "class='btn btn-danger'");

function labelToStatus($settings){
	$today = new Datetime();
	$startDate = $settings->getStartDate(); 
	$endDate = $settings->getEndDate(); 
	$isInPeriod = validateDateInPeriod($today, $startDate, $endDate, TRUE);
	if($isInPeriod){
		echo "<span class='label label-success'>".SelectionProcessConstants::OPEN_FOR_SUBSCRIPTIONS."</span>";
	}
	else{
		$before = $today < $startDate;
		if($before){
			echo "<span class='label label-warning'>".SelectionProcessConstants::NOT_DISCLOSED."</span>";
		}
		else{
			echo "<span class='label label-danger'>".SelectionProcessConstants::INSCRIPTIONS_CLOSED."</span>";
		}
	}
}