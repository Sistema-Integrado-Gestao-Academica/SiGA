<h2 class="principal">Definição de datas do Processo Seletivo <b><i><?=$selectiveprocess->getName()?></i></b> </h2>

<?php 
	$processId = $selectiveprocess->getId();
	$settings = $selectiveprocess->getSettings();
    $processName = $selectiveprocess->getName();
	
	echo "<ul class='timeline'>";
		// Notice Divulgation
		writeTimelineLabel("white", "Divulgação do Edital");
	    if($processDivulgation){
	        $text = $processDivulgation['description'];
	        $link = site_url('download_notice/'.$processId.'/'.$courseId);
	        $bodyText = function(){
	            echo "Clique para baixar.";
	        };
	        $footer = "";
	        $date = convertDateTimeToDateBR($processDivulgation['date']);
	        $today = new Datetime();
			$today = $today->format("d/m/Y");
	        if($date > $today){
	            $footer = function() use ($processId, $courseId, $processName, $date, $text){
				    echo "<button data-toggle='collapse' data-target=#define_date_form class='btn btn-primary'>Editar data</button>";
				    echo "<br>";
				    echo "<br>";
				    echo "<div id='define_date_form' class='collapse'>";
				    echo "<div class='alert alert-info'> Definindo uma data de divulgação do edital você também deve definir uma descrição para a divulgação.</div>";
				    echo "<br>";
		        	formOfDateDivulgation($processId, $processName, $courseId, $date, $text);
	        	};
		    }
	    }
	    else{
	        $text = "Data não definida";
	        $bodyText = function(){ 
	            echo "Você pode definir uma data ou divulgar o processo seletivo agora.";
	        };
	        $date = "";
	        $link = "#";
	        $footer = function() use ($processId, $courseId, $processName){
	        	echo anchor("#", "Divulgar agora", "class='btn btn-success'");
			    echo "&nbsp";
			    echo "<button data-toggle='collapse' data-target=#define_date_form class='btn btn-primary'>Definir data</button>";
			    echo "<br>";
			    echo "<br>";
			    echo "<div id='define_date_form' class='collapse'>";
			    echo "<div class='alert alert-info'> Definindo uma data de divulgação do edital você também deve definir uma descrição para a divulgação.</div>";
			    echo "<br>";
	        	formOfDateDivulgation($processId, $processName, $courseId);
	        };
	    }
        echo "<li>";
            echo "<i class='fa fa-calendar-o bg-blue'></i>";
            echo "<div id='divulgation' class='timeline-item'>";
                writeTimelineItem($text, $date, $link, $bodyText, $footer);
            echo "</div>";
        echo "</li>";


	    // Subscription
	    writeTimelineLabel("blue", "Inscrição");
	    if($settings){
	        $text = "Período de inscrições";
	        $bodyText = function() use ($settings){
	            echo "<b>Data de início:</b><br>";
	            $startDate = $settings->getFormattedStartDate();
	            echo $startDate;
	            $endDate = $settings->getFormattedEndDate();
	            echo "<b><br>Data de fim:</b><br>";
	            echo $endDate;
	            echo "<br><br>";
	            alert(function(){
	                echo "<h5>Para editar o período de inscrição você deve editar o processo seletivo.</h5>";
	            }, "info", FALSE, "info", $dismissible=TRUE);
	        };
	        echo "<li>";
	            echo "<i class='fa fa-calendar-o bg-blue'></i>";
	            echo "<div id='subscription' class='timeline-item'>";
	                writeTimelineItem($text, FALSE, "#", $bodyText);
	            echo "</div>";
	        echo "</li>";
	    }


	    // Phases
	    $phases = $settings->getPhases();
	    if($phases){
	        foreach ($phases as $phase) {
	            $phaseId = $phase->getPhaseId();
	            $phaseName = $phase->getPhaseName();
	            writeTimelineLabel("white", $phaseName);
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
	                echo "<i class='fa fa-calendar-o bg-blue'></i>";
	                echo "<div id='phase_{$phaseId}' class='timeline-item'>";
	                    writeTimelineItem($text, FALSE, "#", $bodyText, "");
	                echo "</div>";
	            echo "</li>";

	        }
	    }

    echo "</ul>";
?>

<?= anchor(
	"program/selectiveprocess/courseSelectiveProcesses/{$courseId}",
	"Voltar",
	"class='btn btn-danger'"
); ?>

