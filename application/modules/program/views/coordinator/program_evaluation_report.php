<!-- C3 js scripts and CSS -->
<link rel="stylesheet" href=<?=base_url("css/c3.min.css")?>>
<script src=<?=base_url("js/d3.v3.min.js")?>></script>
<script src=<?=base_url("js/c3.min.js")?>></script>
<!--  -->

<h2 class="principal"><i class="fa fa-bar-chart"></i> Gráfico de Pontuação das Produções do <b><?= $program['acronym'] ?></b></h2>

<?php if($basePrograms): ?>
<h4><i class="fa fa-list"></i> Programas base:</h4>
<?php showBasePrograms($basePrograms); ?>
<?php else: ?>
    <?= alert(function(){
            echo "<h4>Nenhum programa base cadastrado.</h4>";
        }); ?>
<?php endif; ?>

<?php if ($currentYear != FALSE){

    selectPeriodForm($currentYear, $program['id_program'], $minimumYear);

    ?>
    <br><br>

    <div class='row'>
        <div id="collaboration_indicator_table">
            <?= collaborationIndicatorTable($collaborationIndicators, $basePrograms); ?>
        </div>
    </div>
    <div class='row'>
        <div id="chart_data" style="display: none;">
            <?= $chartData ?>
        </div>

        <div id="chart"></div>
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
        "Pontuação de Produções",
        "Quantidade de Professores",
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
                echo $baseProgram['productions'];
                echo "</td>";

                echo "<td>";
                echo $baseProgram['teachers'];
                echo "</td>";

                echo "<td>";
                echo ($baseProgram['productions'] / $baseProgram['teachers']);
                echo "</td>";

            echo "</tr>";
        }

        buildTableEndDeclaration();
    }
}

function selectPeriodForm($currentYear, $programId, $minimumYear){

    $startYearInput = array(
        "name" => "start_year_period",
        "id" => "start_year_period",
        "type" => "number",
        "placeholder" => "Ano de início",
        "class" => "form-control",
        "step" => 1,
        "max" => $currentYear,
        "min" => $minimumYear
    );

    $endYearInput = array(
        "name" => "end_year_period",
        "id" => "end_year_period",
        "type" => "number",
        "placeholder" => "Ano de fim",
        "class" => "form-control",
        "step" => 1,
        "max" => $currentYear,
        "min" => $minimumYear
    );

    $hidden = array(
        'id' => "program_id",
        'name' => "program_id",
        'type' => 'hidden',
        'value' => $programId
    );


    echo "<div class='input-group'>";
            echo "<h4><i class='fa fa-filter'></i><i class='fa fa-calendar'></i> Escolha um período para filtrar os resultados</h4>";
        echo "<div class='row'>";
            echo "<div class='col-md-4'>";
                echo form_input($startYearInput);
            echo "</div>";
            echo "<div class='col-md-4'>";
                echo form_input($endYearInput);
            echo "</div>";

            echo form_input($hidden);

            echo "<div class='col-md-4'>";
                echo "<span class='input-group-btn'>";
                    echo form_button(array(
                        "name" => "load_graphic_btn",
                        "id" => "load_graphic_btn",
                        "class" => "btn btn-success",
                        "content" => "<i class='fa fa-refresh'></i> Atualizar gráfico",
                        "type" => "button"
                    ));
                echo "</span>";
            echo "</div>";

        echo "</div>";

    echo "</div>";
}

function collaborationIndicatorTable($collaborationIndicators, $basePrograms){

    // Treating when $basePrograms is null
    $basePrograms = $basePrograms !== FALSE ? $basePrograms : array();

    if(!empty($collaborationIndicators)){

        buildTableDeclaration();

        $headers = array(
            "Ano",
            "Pontuação (Prod)",
            "Quantidade de Professores (Prof)",
            "Indicador de Colaboração (Prod/Prof)"
        );

        foreach ($basePrograms as $baseProgram) {
            $headers[] = "Comparativo - <i>".$baseProgram['name']."</i>";
        }

        buildTableHeaders($headers);

        foreach($collaborationIndicators as $year => $collaborationIndicator){

            $productionsPoints = $collaborationIndicator['productions_points'];

            echo "<tr>";

                echo "<td>";
                echo $year;
                echo "</td>";

                echo "<td>";
                echo $productionsPoints;
                echo "</td>";

                echo "<td>";
                echo $collaborationIndicator['teachers'];
                echo "</td>";

                echo "<td>";
                echo $collaborationIndicator['indicator'];
                echo "</td>";

                foreach ($basePrograms as $baseProgram) {
                    $baseProgramPoints = $baseProgram['productions'];
                    $pointsDiff = $baseProgramPoints - $productionsPoints;
                    $msg = $pointsDiff >= 0 ? "Atrás" : "Na frente";
                    $msg .= " por <b>{$pointsDiff}</b> pontos.";
                    echo "<td>";
                    echo $msg;
                    echo "</td>";
                }

            echo "</tr>";
        }

        buildTableEndDeclaration();
    }
}
?>
