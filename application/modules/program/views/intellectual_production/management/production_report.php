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
            echo bold("<li>{$program['program_name']} ({$program['acronym']})</li>");
        }
    ?>
</ul>

<br>
<h3><i class="fa fa-bar-chart"></i> Gráfico de produções por ano: <br><small>As produções são agrupadas pela classificação Qualis.</small></h3>

<br><br>
<?php 

$formInput = array(
    "name" => "report_year",
    "id" => "report_year",
    "type" => "number",
    "placeholder" => "Informe o ano para gerar o relatório",
    "class" => "form-control",
    "step" => 1,
    "max" => $currentYear,
    "min" => 2000 // Year 2000 at min
);
    echo "<div class='row text-center'>";
        echo "<h4><i class='fa fa-filter'></i><i class='fa fa-calendar'></i> Escolha um ano para filtrar os resultados</h4>";
    echo "</div>";
    
    selectYearForm($currentYear, array($formInput)); ?>
<br><br>

<div id="chart_data" style="display: none;">
    <?= $chartData ?>
</div>

<div id="chart"></div>

<script src=<?=base_url("js/production_management.js")?>></script>

<?php else : ?>
    <?= callout("info", "Você não é coordenador(a) de nenhum programa. Apenas os coordenadores dos programas podem acessar o Relatório de Produções dos seus respectivos programas.") ?>
<?php endif ; ?>
