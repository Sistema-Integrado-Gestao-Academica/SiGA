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
	<i class='fa fa-bullhorn'> Divulgações </i> &nbsp&nbsp
	<i class='fa fa-group'> Incrições realizadas </i>
	<i class='fa fa-arrow-circle-o-right'> Ir para próxima fase </i>
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
				echo lang($process->getStatus());
				$noticeWithAllConfig = $settings->isDatesDefined() && $settings->isNeededDocsSelected() && $settings->isTeachersSelected();
				if(!$noticeWithAllConfig){
					$message = "<br><h6 class='text-warning'><i class='fa fa-warning'></i>Edite o processo seletivo para terminar de configurá-lo.</h6>";
                    $message .= "<h6 class='text-warning'>Você só poderá divulgá-lo quando terminar a configuração.</h6>";
                    echo "<br>".lang(SelectionProcessConstants::INCOMPLETE_CONFIG).$message	;
				}
				if($process->getSuggestedPhase()){
                    echo "<br>".lang(SelectionProcessConstants::WAITING_NEXT_PHASE);
				}
			echo "</td>";

			echo "<td>";
				createProcessModal($process, $settings, $processesTeachers, $processesDocs, $processesResearchLines);
				echo "<a href='#selectiveprocessmodal{$processId}' data-toggle='modal' class='btn btn-primary'><i class='fa fa-eye'></i></a>";
				echo "&nbsp";
				$courseId = $course[Course_model::ID_ATTR];
				echo anchor("edit_selection_process/{$processId}", "<i class='fa fa-edit'></i>", "class='btn btn-success'");
				echo "&nbsp";
				$classDivulgationsButton = $noticeWithAllConfig ?  "class='btn bg-navy'" : "class='btn bg-navy disabled'";
				echo anchor("selection_process/secretary_divulgations/{$processId}", "<i class='fa fa-bullhorn'></i>", $classDivulgationsButton);
				echo "&nbsp";
				echo anchor("selection_process/homolog/subscriptions/{$processId}", "<i class='fa fa-group'></i>", "class='btn btn-default'");
				echo "&nbsp";
				if($process->getSuggestedPhase()){
					createNextPhaseModal($process);
					echo "<a href='#nextphasemodal{$processId}' data-toggle='modal' class='btn btn-info'><i class='fa fa-arrow-circle-o-right'></i></a>";
				}

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
	    echo "<div class='col-lg-4'>";
			echo "<h4><i class='fa fa-user'></i> ".$process->getFormmatedType()."</h4>";
		echo "</div>";
		echo "<div class='col-lg-3'>";
			echo "<h4><i class='fa fa-group'></i> Vagas: ".$process->getVacancies()."</h4>";
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

	    echo "<div class='col-lg-5'>";
			echo "<center><h4><i class='fa fa-calendar-o'></i> Inscrições: <br>";
			echo $date."</center></h4>";
		echo "</div>";
	echo "</div>";

	echo "<hr>";
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
		    	$formattedStartDate = $phase->getFormattedStartDate();
		    	$formattedEndDate = $phase->getFormattedEndDate();
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
						echo "<hr>";
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

function createNextPhaseModal($process){

	$phasesWithStatus = array(
            SelectionProcessConstants::IN_HOMOLOGATION_PHASE => SelectionProcessConstants::HOMOLOGATION_PHASE, 
            SelectionProcessConstants::IN_PRE_PROJECT_PHASE => SelectionProcessConstants::PRE_PROJECT_EVALUATION_PHASE, 
            SelectionProcessConstants::IN_WRITTEN_TEST_PHASE => SelectionProcessConstants::WRITTEN_TEST_PHASE, 
            SelectionProcessConstants::IN_ORAL_TEST_PHASE => SelectionProcessConstants::ORAL_TEST_PHASE
        );

	$processId = $process->getId();
	$suggestedPhase = $process->getSuggestedPhase();
	$phaseName = $phasesWithStatus[$suggestedPhase];
	$courseId = $process->getCourse();

	$body = function() use ($suggestedPhase, $phaseName, $processId, $courseId){
		$hidden = array(
			'id' => 'suggested_phase',
			'name' => 'suggested_phase',
			'type' => 'hidden',
			'value' => $suggestedPhase
		);

		echo form_input($hidden);

		echo "<h4>Deseja passar para a fase de <b>".$phaseName."</b>?</h4>";
	};

	$footer = function(){
		echo form_button(array(
		    "class" => "btn btn-danger pull-left",
		    "content" => "Não",
		    "type" => "button",
		    "data-dismiss"=>'modal'
		));
		
		echo form_button(array(
		    "class" => "btn btn-success",
		    "content" => "Sim",
		    "type" => "submit"
		));
	};
	$processName = $process->getName();
	$formPath = "selection_process/next_phase/{$processId}/{$courseId}";
	newModal("nextphasemodal".$processId, "Passar para a próxima fase</b>", $body, $footer, $formPath);
}

?>