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
				if(!$noticeWithAllConfig[$processId]){
					echo "<h6 class='text-warning'><i class='fa fa-warning'></i>Edite o processo seletivo para terminar de configurá-lo.</h6>";
					echo "<h6 class='text-warning'>Você só poderá divulgá-lo quando terminar a configuração.</h6>";
				}

			echo "</td>";

			echo "<td>";
				$classDivulgationsButton = $noticeWithAllConfig[$processId] ?  "class='btn bg-navy'" : "class='btn bg-navy disabled'";
				createProcessModal($process, $settings, $processesTeachers, $processesDocs, $processesResearchLines);
				echo "<a href='#selectiveprocessmodal{$processId}' data-toggle='modal' class='btn btn-primary'><i class='fa fa-eye'></i></a>";
				echo "&nbsp";
				$courseId = $course[Course_model::ID_ATTR];
				echo anchor("edit_selection_process/{$processId}", "<i class='fa fa-edit'></i>", "class='btn btn-success'");
				echo "&nbsp";
				echo anchor("selection_process/secretary_divulgations/{$processId}", "<i class='fa fa-bullhorn'></i>", $classDivulgationsButton);

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


function createProcessModal($process, $settings, $processesTeachers, $processesDocs, $processesResearchLines){

	$processId = $process->getId();
	$body = function() use ($process, $settings, $processesTeachers, $processesDocs, $processesResearchLines){
		$processId = $process->getId();
		echo "<ul class='nav nav-tabs nav-justified'>";
			echo "<li class='active'>";
				echo "<a href='#basic_data_tab_{$processId}' class='btn btn-tab' data-toggle='tab'>Dados básicos </a>";
			echo "</li>";
			echo "<li>";
				echo "<a href='#teachers_tab_{$processId}' class='btn btn-tab' data-toggle='tab'>Comissão de Seleção </a>";
			echo "</li>";
			echo "<li>";
				echo "<a href='#subs_config_tab_{$processId}' class='btn btn-tab' data-toggle='tab'>Configurações de inscrição </a>";
			echo "</li>";
		echo "</ul>";
		$phases = $settings->getPhases();

		echo "<div class='tab-content'>";
			echo "<div class='tab-pane fade in active' id='basic_data_tab_{$processId}'>";
				showBasicDataTab($process, $phases, $settings);
			echo "</div>";
			echo "<div class='tab-pane fade' id='teachers_tab_{$processId}'>";
				showTeachersTab($processesTeachers[$processId]);
			echo "</div>";
			echo "<div class='tab-pane fade' id='subs_config_tab_{$processId}'>";
				showSubsConfigTab($processesDocs[$processId], $processesResearchLines[$processId]);
			echo "</div>";
			
		echo "</div>";

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

function showBasicDataTab($process, $phases, $settings){
	echo "<div class='row' align='left'>";
	    echo "<div class='col-lg-5'>";
			echo "<h4><i class='fa fa-user'></i> Edital de ".$process->getFormmatedType()."</h4><br>";
		echo "</div>";

		$startDate = $settings->getStartDate();
	    $endDate = $settings->getEndDate();
	    if($startDate != NULL && $endDate !== NULL){
	    	$formattedStartDate = $settings->getFormattedStartDate();
	    	$formattedEndDate = $settings->getFormattedEndDate();
	        $date = $formattedStartDate." a ".$formattedEndDate;
	    }
	    else{
	       	$date = "Período de inscrições não definido";
	    }

	    echo "<div class='col-lg-7'>";
			echo "<h4><i class='fa fa-calendar-o'></i> Inscrições: ".$date."</h4>";
		echo "</div>";
	echo "</div>";
	
	$phasesOrder = $settings->getPhasesOrder();
	$validPhases = !empty($phases) && !is_null($phases);
	if($validPhases){
		echo "<div class='row'>";
		foreach ($phases as $phase) {
			$phaseId = $phase->getPhaseId();
			$phaseName = $phase->getPhaseName();
			$startDate = $phase->getStartDate();
			$endDate = $phase->getEndDate();
			if($startDate != NULL && $endDate !== NULL){
		    	$formattedStartDate = $settings->getFormattedStartDate();
		    	$formattedEndDate = $settings->getFormattedEndDate();
		        $date = $formattedStartDate." a ".$formattedEndDate;
		    }
		    else{
		       	$date = "Período não definido";
		    }
		    echo "<div class='col-lg-6'>";
				echo "<div class='small-box bg-green'>";
		    		echo "<div class='inner'>";
		        echo "<h4><b>";
		            echo $phaseName;
		        echo "</b></h4>";
		        echo "<p>";
		            echo "<i class='fa fa-calendar-o'></i> ";
		            echo $date;
		            echo "<br>";
		            $weight = $phaseId != 1 ? $phase->getWeight() : "0";
					echo "Peso: ".$weight;
		            echo "<br>";
					$grade = $phaseId != 1 ? $phase->getGrade() : "0";
					echo "Nota de Corte: ".$grade;
		        echo "</p>";
		    		echo "</div>";
				echo "</div>";
			echo "</div>";
		}
		echo "</div>";
	}
}

function showTeachersTab($processTeachers){

	echo "<br>";
	if($processTeachers){
		echo "<div class='box box-solid'>";
		    echo "<div class='box-header'>";
		        echo "<i class='fa fa-group'></i>";
		        echo "<h3 class='box-title'>Professores vinculados</h3>";
		    echo "</div>";
		    echo "<div class='box-body' align='left'>";
		        echo "<dl>";
		        	foreach ($processTeachers as $teacher) {
		        		$teacherName = $teacher['name'];
		        		$teacherEmail = $teacher['email'];
		            	echo "<dt>{$teacherName}</dt>";
		            	echo "<dd>{$teacherEmail}</dd>";
		        	}
		        echo "</dl>";
		    echo "</div>";
		echo "</div>";
	}
	else{
		callout("info", "Nenhum professor vinculado ao processo.");
	}

}

function showSubsConfigTab($processDocs, $researchLines){
	
	echo "<br>";
	if($processDocs){
		echo "<div class='box box-solid'>";
		    echo "<div class='box-header'>";
		        echo "<i class='fa fa-file'></i>";
		        echo "<h3 class='box-title'>Documentos necessários para inscrição</h3>";
		    echo "</div>";
		    echo "<div class='box-body'>";
		        echo "<ul class='list-unstyled row' align='left'>";
		        	foreach ($processDocs as $doc) {
		        		$docName = $doc['doc_name'];
		            	echo "<li class='col-lg-6'>{$docName}</li>";
		        	}
		        echo "</ul>";
		    echo "</div>";
		echo "</div>";
	}
	echo "<hr>";
	if($researchLines){
		echo "<div class='box box-solid'>";
		    echo "<div class='box-header'>";
		        echo "<i class='fa fa-bars'></i>";
		        echo "<h3 class='box-title'>Linhas de Pesquisa</h3>";
		    echo "</div>";
		    echo "<div class='box-body'>";
		        echo "<ul class='list-unstyled row' align='left'>";
		        	foreach ($researchLines as $researchLine) {
						$name = $researchLine['description'];
		            	echo "<li class='col-lg-6'>{$name}</li>";
		        	}
		        echo "</ul>";
		    echo "</div>";
		echo "</div>";
	}
}

?>