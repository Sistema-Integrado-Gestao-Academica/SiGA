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



