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