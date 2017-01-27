<?php

function writeTimelineLabel($color, $text){
    
    echo "<li class='time-label'>";
        echo "<span class='bg-".$color."'>";
            echo $text;
        echo "</span>";
    echo "</li>";
}

function writeTimelineItem($text, $date, $link, $bodyText = "", $footer = ""){
    if($date){
        echo "<span class='time'><i class='fa fa-calendar'></i>". $date."</span>";
    }
    echo "<h3 class='timeline-header'><a href='{$link}'>".$text."</h3></a>";
    echo "<div class='timeline-body'>";
        if(!empty($bodyText)){
            $bodyText();
        }
    echo "</div>";
    echo "<div class='timeline-footer'>";
        if(!empty($footer)){
            $footer();
        }
    echo "</div>";  
}

function writeTimelineItemToAddItem($textToInput, $bodyText = "", $footer = ""){
    
    $today = new Datetime();
    $date = $today->format("d/m/Y");
    echo "<span class='time'><i class='fa fa-calendar'></i>". $date."</span>";
    
    echo form_open_multipart("program/selectiveprocess/addDivulgation", array( 'id' => 'add_divulgation_form' ));
    echo "<h3 class='timeline-header'><a href='#new_divulgation'>";
        $textToInput();
    echo "</h3></a>";
    echo "<div class='timeline-body'>";

        if(!empty($bodyText)){
            $bodyText();
        }
    echo "</div>";
    echo "<div class='timeline-footer'>";
        if(!empty($footer)){
            $footer();
        }
    echo "</div>";  
    echo form_close();
}




