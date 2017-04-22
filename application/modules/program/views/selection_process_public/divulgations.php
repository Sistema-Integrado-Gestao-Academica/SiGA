<h2 class="principal">Divulgações do processo seletivo: <b><i><?=$selectiveprocess->getName()?></i></b> </h2>
<?php
echo "<hr>";

$settings = $selectiveprocess->getSettings();
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
showDivulgations($selectiveprocess, $processDivulgations, $phasesName, TRUE);

echo "<br>";

$courseId = $selectiveprocess->getCourse();
echo anchor("/selection_process/public", "Voltar", "class='btn btn-danger'");
