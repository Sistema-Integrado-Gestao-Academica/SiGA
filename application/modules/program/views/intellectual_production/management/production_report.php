<!-- C3 js scripts and CSS -->
<link rel="stylesheet" href=<?=base_url("css/c3.min.css")?>>
<script src=<?=base_url("js/d3.v3.min.js")?>></script>
<script src=<?=base_url("js/c3.min.js")?>></script>
<!--  -->

<h2 class="principal"> <i class="fa fa-file-text-o"></i> Relatório de produções</h2>

<?php if (!empty($programs)) :

    alert(function() use ($user){
        echo "<h4><i>{$user->getName()}</i>, o Relatório de Produções é baseado nas produções realizadas por <b>discentes e docentes</b>, tanto como <b>autores</b> quanto <b>co-autores</b>, dos cursos dos programas os quais você é coordenador(a).</h4>";
    });
?>

<h3><i class="fa fa-list"></i> Programas os quais você é coordenador(a): </h3>
<ul>
    <?php
        foreach($programs as $program){
            echo bold("<li><a href='#program_data_{$program['id_program']}'>{$program['program_name']} ({$program['acronym']})</a></li>");
        }
    ?>
</ul>

<?php foreach($programs as $program): ?>

    <div id="program_data_<?= $program['id_program'] ?>" class="row">
        <br>
        <h3 class="text-center"><?= $program['acronym'] ?></h3>
        <h3><i class="fa fa-bar-chart"></i>
            Gráfico de produções por ano do programa <b><i><?= $program['program_name']?></i></b>: <br>
            <small>As produções são agrupadas pela classificação Qualis.</small>
        </h3>

        <br><br>
        <?php
            selectYearForm($currentYear, $program); ?>
        <br><br>


        <div id="chart_data" style="display: none;">
            <?= $chartData ?>
        </div>

        <?php echo "<div id='program_{$program['id_program']}_chart'></div>";?>
    </div>

<?php endforeach; ?>

<script src=<?=base_url("js/production_management.js")?>></script>

<?php else : ?>
    <?= callout("info", "Você não é coordenador(a) de nenhum programa. Apenas os coordenadores dos programas podem acessar o Relatório de Produções dos seus respectivos programas.") ?>
<?php endif ; ?>

<?php

function selectYearForm($currentYear, $program){

    $formInput = array(
        "name" => "report_year_{$program['id_program']}",
        "id" => "report_year_{$program['id_program']}",
        "type" => "number",
        "placeholder" => "Informe o ano para gerar o relatório",
        "class" => "form-control",
        "step" => 1,
        "max" => $currentYear,
        "min" => 2000 // Year 2000 at min
    );

    $submitBtn = array(
        "name" => "load_graphic_btn_{$program['id_program']}",
        "id" => "load_graphic_btn_{$program['id_program']}",
        "class" => "btn btn-success",
        "content" => "<i class='fa fa-refresh'></i> Atualizar gráfico",
        "type" => "button",
        "onclick" => "updateProgramProductionsChart(event, {$program['id_program']})"
    );

    echo "<div class='row text-center'>";
        echo "<h4><i class='fa fa-filter'></i><i class='fa fa-calendar'></i> Escolha um ano para filtrar os resultados</h4>";
    echo "</div>";

    echo "<div class='row'>";
        echo "<div class='col-md-6 col-md-offset-3'>";
            echo "<div class='input-group'>";
                echo form_input($formInput);
                echo "<span class='input-group-btn'>";
                echo form_button($submitBtn);

                echo "</span>";
            echo "</div>";
        echo "</div>";
    echo "</div>";
}

?>