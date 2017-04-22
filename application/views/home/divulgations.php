<h2 class="principal">Divulgações do processo seletivo: <b><i><?=$process->getName()?></i></b> </h2>
<?php 
echo "<hr>";

$settings = $process->getSettings();
$phases = $settings->getPhases();

$phasesName = array();
if($phases !== FALSE){
    foreach ($phases as $phase) {
        $id = $phase->getPhaseId();
        $name = $phase->getPhaseName();
        $phasesName[$id] = $name;
    }
}
// True because is a guest user
showDivulgations($process, $processDivulgations, $phasesName, TRUE);

echo "<br>";

$courseId = $process->getCourse();
echo anchor("/", "Voltar", "class='btn btn-danger'");