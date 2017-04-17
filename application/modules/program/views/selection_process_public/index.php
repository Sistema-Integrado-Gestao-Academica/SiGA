<h2 class="principal">Processos seletivos abertos</h2>

<?php
if(!empty($openSelectiveProcesses)){

    $course = "";
    foreach($openSelectiveProcesses as $index => $process){

        $processName = $process->getName();
        $processId = $process->getId();
        $courseId = $process->getCourse();

        $processCourse = $courses[$processId];
        if($course !== $processCourse){
            $course = $processCourse;
            if($index !== 0){
                    echo "</div>";
                echo "</div>";
            }
            echo "<div class='panel panel-primary'>";
                echo "<div class='panel-heading'>";
                    echo "<h3 class='panel-title'>Curso: {$course}</h3>";
                echo "</div>";
                echo "<div class='panel-body'>";
        }
        echo "<h4>";
        echo anchor("selection_process/guest/{$processId}", $processName);
        echo "</h4>";
        echo "<hr>";

    }
}
?>