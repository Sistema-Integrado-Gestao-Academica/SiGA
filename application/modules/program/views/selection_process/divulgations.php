<h2 class="principal">Divulgações do processo seletivo: <b><i><?=$selectiveprocess->getName()?></i></b> </h2>
<?php 

createDivulgationsModal($selectiveprocess, $processDivulgations);

$processId = $selectiveprocess->getId();
$courseId = $selectiveprocess->getCourse();

echo "<h4><a href='#divulgationsmodal{$processId}' data-toggle='modal'><i class='fa fa-plus-circle'></i>Fazer nova divulgação</a></h4>";

echo "<hr>";
showDivulgations($selectiveprocess, $processDivulgations);

echo "<br>";

echo anchor("program/selectiveprocess/courseSelectiveProcesses/{$courseId}", "Voltar", "class='btn btn-danger'");