<!-- C3 js scripts and CSS -->
<link rel="stylesheet" href=<?=base_url("css/c3.min.css")?>>
<script src=<?=base_url("js/d3.v3.min.js")?>></script>
<script src=<?=base_url("js/c3.min.js")?>></script>
<!--  -->

<h2 class="principal"><i class="fa fa-bar-chart"></i> Gráfico de Pontuação das Produções do <b><?= $program['acronym'] ?></b></h2>

<h4><i class="fa fa-list"></i> Programas base:</h4>
<?php showBasePrograms($basePrograms); ?>

<?php if ($currentYear != FALSE){

    selectPeriodForm($currentYear, $program['id_program'], $minimumYear); 
    
    ?>
    <br><br>

    <div class='row'>
        <div class='col-md-4'>
            <div id="collaboration_indicator_table">
                <?= collaborationIndicatorTable($collaborationIndicators); ?>
            </div>

        </div>
        <div class='col-md-8'>
            <div id="chart_data" style="display: none;">
                <?= $chartData ?>
            </div>

            <div id="chart"></div>
        </div>
    </div>

    <script src=<?=base_url("js/evaluation_report.js")?>></script>

<?php }

    else{
        echo "<td colspan=2>";
        callout("info", "Este programa não possui nenhuma avaliação.");
        echo "</td>";
    }
?>

<?php

function showBasePrograms($programs){

    buildTableDeclaration();

    buildTableHeaders(array(
        "Programa",
        "Nota",
        "Indicador de colaboração",
    ));

    if(!empty($programs)){

        foreach($programs as $baseProgram){

            echo "<tr>";
                
                echo "<td>";
                echo $baseProgram['name'];
                echo "</td>";

                echo "<td>";
                echo $baseProgram['grade'];
                echo "</td>";

                echo "<td>";
                echo ($baseProgram['productions'] / $baseProgram['teachers']);
                echo "</td>";

            echo "</tr>";
        }

        buildTableEndDeclaration();
    }
}

?>
