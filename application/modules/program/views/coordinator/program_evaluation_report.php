<!-- C3 js scripts and CSS -->
<link rel="stylesheet" href=<?=base_url("css/c3.min.css")?>>
<script src=<?=base_url("js/d3.v3.min.js")?>></script>
<script src=<?=base_url("js/c3.min.js")?>></script>
<!--  -->

<h2 class="principal"><b><?= $program['program_name'] ?></b></h2>

<?php if ($currentPeriod != FALSE){ ?>

    <br>
    <h3><i class="fa fa-bar-chart"></i> Gráfico de Pontuação das Produções de <b><?= $currentPeriod ?></b></h3>
  
    <br><br>
    <?php 
    $startYearInput = array(
        "name" => "start_year_period",
        "id" => "start_year_period",
        "type" => "number",
        "placeholder" => "Informe o ano de início",
        "class" => "form-control",
        "step" => 1,
        "max" => $currentYear,
        "min" => $minimumYear 
    );

    $endYearInput = array(
        "name" => "end_year_period",
        "id" => "end_year_period",
        "type" => "number",
        "placeholder" => "Informe o ano de fim",
        "class" => "form-control",
        "step" => 1,
        "max" => $currentYear,
        "min" => $minimumYear 
    );

    echo "<div class='row text-center'>";
        echo "<h4><i class='fa fa-filter'></i><i class='fa fa-calendar'></i> Escolha um período para filtrar os resultados</h4>";
    echo "</div>";
    
    selectYearForm($currentYear, array($startYearInput, $endYearInput), $program['id_program']); ?>
    <br><br>

    <div id="chart_data" style="display: none;">
        <?= $chartData ?>
    </div>

    <div id="chart"></div>

    <script src=<?=base_url("js/evaluation_report.js")?>></script>

<?php }

    else{
        echo "<td colspan=2>";
        callout("info", "Este programa não possui nenhuma avaliação.");
        echo "</td>";
    }
?>

