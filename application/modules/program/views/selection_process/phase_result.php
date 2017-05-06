<h2 class="principal">Processo Seletivo <b><i><?=$processName?></i></b> </h2>
<?php $resultLabel = $phaseName == "Final" ? "<b>Resultado Final</b><br>Candidatos selecionados" : "Resultado da fase de <b>{$phaseName}</b>";?>
<div class="box box-primary">
    <div class="box-header">
        <h3 class="box-title"><?=$resultLabel?></h3>
    </div>
    <div class="box-body">
        <?php 
        if($phaseName == SelectionProcessConstants::HOMOLOGATION_PHASE){
            createHomologationTable($candidates->candidates);
        }
        elseif($phaseName == "Final"){
            createFinalTable($candidates->candidates);
        }else{
            createTableByPhase($candidates->candidates);
        } ?>
    </div><!-- /.box-body -->
    <div class="box-footer no-print">
        <button id= "print_report_btn" class= "btn btn-primary btn-flat" type= "submit"><i class='fa fa-print'></i> Imprimir Resultado</button>
        <?= anchor(
        "selection_process/results/{$processId}",
        "Voltar",
        "class='btn btn-danger pull-right'"
    ); ?>
    </div><!-- /.box-footer-->
</div>

<?php
    function createHomologationTable($candidates){
        buildTableDeclaration(); 
        buildTableHeaders(array('Candidatos homologados'));
        foreach ($candidates as $candidateId => $result) {
            echo "<tr>";
            echo "<td><h4><center>{$candidateId}</center></h4></td>";
            echo "</tr>";
        }
        buildTableEndDeclaration();
    }

    function createTableByPhase($candidates){
        buildTableDeclaration(); 
        buildTableHeaders(array('Candidato', 'Nota', 'Resultado'));
        foreach ($candidates as $candidateId => $result) {
            $average = $result->average;
            $labelResult = $result->label;
            echo "<tr>";
            echo "<td><h4><center>{$candidateId}</center></h4></td>";
            echo "<td><center>{$average}</center></td>";
            echo "<td><center>{$labelResult}</center></td>";
            echo "</tr>";
        }
        buildTableEndDeclaration();
    }

    function createFinalTable($candidates){
        buildTableDeclaration(); 
        $headers = array('Classificação', 'Candidato');
        $first = key($candidates);
        $candidatesHeaders = clone $candidates->$first;
        unset($candidatesHeaders->final_average);
        foreach ($candidatesHeaders as $header) {
            $headers[] = "Nota da Fase ".$header->phaseName."- Peso ".$header->phaseWeight;
        }
        $headers[] = "Nota Final";
        buildTableHeaders($headers);
        $classificacao = 1;
        foreach ($candidates as $candidateId => $result) {
            $finalAverage = number_format($result->final_average, 2, ',', ' ');
            unset($result->final_average);
            echo "<tr>";
            echo "<td><center>{$classificacao}º</center></td>";
            echo "<td><h4><center>{$candidateId}</center></h4></td>";
            foreach ($result as $phaseResult) {
                $average = $phaseResult->average;
                echo "<td><center>{$average}</center></td>";
            }
            echo "<td><center>{$finalAverage}</center></td>";
            echo "</tr>";
            $classificacao++;
        }
        buildTableEndDeclaration();
    }
?>

<script>
    $(document).ready(function(){
        window.print();

        $("#print_report_btn").click(function(){
            window.print();
        });
    });
</script>