<?php

function writeTimelineLabel($color, $text){
    
    echo "<li class='time-label'>";
        echo "<span class='bg-".$color."'>";
            echo $text;
        echo "</span>";
    echo "</li>";
}

function writeTimelineItem($text, $icon, $date, $link, $bodyText, $footer = ""){
    echo "<li>";
        echo "<i class='".$icon." bg-blue'></i>";
        echo "<div class='timeline-item'>";
            if($date){
                echo "<span class='time'><i class='fa fa-calendar'></i>". $date."</span>";
            }
            echo "<h3 class='timeline-header'><a href='{$link}'>".$text."</h3></a>";
            echo "<div class='timeline-body'>";
                $bodyText();
            echo "</div>";
            echo "<div class='timeline-footer'>";
                if(!empty($footer)){
                    $footer();
                }
            echo "</div>";
        echo "</div>";
    echo "</li>";
    
}

function showPhasesSection($settings, $processId){
    
    $phases = $settings->getPhases();
    if($phases){
        foreach ($phases as $phase) {
            writeTimelineLabel("white", $phase->getPhaseName());
            $phaseId = $phase->getPhaseId();
            // $startDate = $phase->getFormmatedStartDate(); 
            // if(!is_null($startDate)){
            //  writeTimelineLabel("green", "Início:".$startDate);
            //  writeTimelineLabel("red", "Fim:".$phases->getFormattedEndDate());
            // }
            // else{

                $text = "Período não definido";
                $icon = "fa fa-calendar-o";
                $bodyText = function() use ($processId, $phaseId){
                    defineDateForm($processId, 'define_date_phase_{$phaseId}', "phase_{$phaseId}_start_date", "phase_{$phaseId}_end_date");
                };
                writeTimelineItem($text, $icon, FALSE, "#", $bodyText, "");
            // }

        }
    }
}

function showDivulgationDateSection($process, $processDivulgation, $showMessage = FALSE){

    $processId = $process->getId();
    $settings = $process->getSettings();
    $processName = $process->getName();
    writeTimelineLabel("white", "Divulgação do Edital");
    if($processDivulgation){
        $date = convertDateTimeToDateBR($processDivulgation['date']);
        writeTimelineLabel("green", "Início:".$date);
    }
    else{
        $text = "Data não definida";
        $bodyText = function(){ 
            echo "Você pode definir uma data ou divulgar o processo seletivo agora.";
            
        };
        $icon = "fa fa-calendar-o";
        $footer = function() use ($processId, $processDivulgation, $processName){
            formOfDateDivulgation($processId, $processName);
        };
        writeTimelineItem($text, $icon, FALSE, "#", $bodyText, $footer);
    }
}

function showDivulgationDateSectionWithError($process, $error){
    
    $processId = $process->getId();
    $processName = $process->getName();
    writeTimelineLabel("white", "Divulgação do Edital");
    $text = "Data não definida - Erro";
    $bodyText = function() use ($error){ 
        echo "<div class='alert alert-danger alert-dismissible' role='alert'>";
        echo $error;
        echo "</div>";
        echo "Definir uma data ou divulgue o processo seletivo agora.";
        
    };
    $icon = "fa fa-warning-o";
    $footer = function() use ($processId, $processName){
        formOfDateDivulgation($processId, $processName);
    };
    writeTimelineItem($text, $icon, FALSE, "#", $bodyText, $footer);
}

function showSubscriptionSection($settings){
    writeTimelineLabel("blue", "Inscrição");
    if($settings){
        writeTimelineLabel("green", "Início:".$settings->getFormattedStartDate());
        writeTimelineLabel("red", "Fim:".$settings->getFormattedEndDate());
    }
}

function formOfDateDivulgation($processId, $processName){
    echo anchor("#", "Divulgar agora", "class='btn btn-success'");
    echo "&nbsp";
    echo "<button data-toggle='collapse' data-target=#define_date_form class='btn btn-primary'>Definir data</button>";
    echo "<br>";
    echo "<br>";
    echo "<div id='define_date_form' class='collapse'>";
    echo "<div class='alert alert-info'> Definindo uma data de divulgação do edital você também deve definir uma descrição para a divulgação.</div>";
    echo "<br>";
    $hidden = array(
        'id' => "process_id",
        'name' => "process_id",
        'type' => "hidden",
        'value' => $processId
    );

    $description = array(
        "name" => "divulgation_description",
        "id" => "divulgation_description",
        "type" => "text",
        "class" => "form-campo",
        "class" => "form-control",
        "placeholder" => "Sugestão: Edital {$processName}"
    );

    $submitBtn = array(
        "id" => "define_divulgation_date",
        "content" => "Definir",
        "class" => "btn bg-primary btn-flat",
        "type" => "submit"
    );

    $startDate = array(
        "name" => 'divulgation_start_date',
        "id" => 'divulgation_start_date',
        "type" => "text",
        "placeholder" => "Informe a data",
        "class" => "form-campo",
        "class" => "form-control"
    );
    echo form_input($hidden);
    echo form_label("Data", "date");
    echo form_input($startDate);
    echo form_label("Descrição", "description");
    echo form_input($description);
    echo "<br>";
    echo form_button($submitBtn);
}