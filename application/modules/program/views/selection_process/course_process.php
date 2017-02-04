
<h2 class="principal">Processos Seletivos do curso <b><i><?=$course[Course_model::COURSE_NAME_ATTR]?></i></b> </h2>

<?php
require_once(MODULESPATH."/program/constants/SelectionProcessConstants.php");

echo anchor(
	"program/selectiveprocess/openSelectiveProcess/{$course[Course_model::ID_ATTR]}",
	"<i class='fa fa-plus-square'></i> Abrir edital para <b>".$course[Course_model::COURSE_NAME_ATTR]."</b>",
	"class = 'btn btn-lg'"
);
?>


<div align='right'>
	<i class='fa fa-eye'> Visualizar </i> &nbsp&nbsp
	<i class='fa fa-edit'> Editar </i> &nbsp&nbsp
	<i class='fa fa-calendar'> Definir datas </i> &nbsp&nbsp
	<i class='fa fa-bullhorn'> Divulgações </i>
</div>

<?php
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

			$processId = $process->getId();
			echo "<td>";
				echo $status[$processId];
			echo "</td>";

			echo "<td>";
				createProcessModal($process, $settings);
				echo "<a href='#selectiveprocessmodal{$processId}' data-toggle='modal' class='btn btn-success'><i class='fa fa-eye'></i></a>";
				echo "&nbsp";
				echo anchor("edit_selection_process/{$processId}/{$course[Course_model::ID_ATTR]}", "<i class='fa fa-edit'></i>", "class='btn btn-primary'");
				echo "&nbsp";
				echo anchor("define_dates_page/{$processId}/{$course[Course_model::ID_ATTR]}", "<i class='fa fa-calendar'></i>", "class='btn btn-warning'");

				echo "&nbsp";
				echo anchor("selection_process/divulgations/{$processId}", "<i class='fa fa-bullhorn'></i>", "class='btn bg-olive'");

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


function createProcessModal($process, $settings){

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
	$processName = $process->getName();
	newModal("selectiveprocessmodal".$processId, "Processo Seletivo: <b>{$processName}</b>", $body, $footer);
}


?>