<!-- C3 js scripts and CSS -->
<link rel="stylesheet" href=<?=base_url("css/c3.min.css")?>>
<script src=<?=base_url("js/d3.v3.min.js")?>></script>
<script src=<?=base_url("js/c3.min.js")?>></script>
<!--  -->

<h2 class="principal">Relatório de produções</h2>

<?php if (!empty($programs)) :

    alert(function() use ($user){
        echo "<h4><i>{$user->getName()}</i>, o Relatório de Produções é baseado nas produções realizadas por <b>discentes e docentes</b>, tanto como <b>autores</b> quanto <b>co-autores</b>, dos cursos dos programas os quais você é coordenador(a).</h4>";
    });
?>

<h4><i class="fa fa-list"></i> Programas os quais você é coordenador(a): </h4>
<ul>
    <?php
        foreach($programs as $program){
            echo bold("<li>{$program['program_name']} ({$program['acronym']})</li>");
        }
    ?>
</ul>

<br>

<div id="graphic_data" style="display: none;">
    <?= $graphicData ?>
</div>

<div id="chart"></div>

<script>
    var graphicData = JSON.parse( $("#graphic_data").text() );

    console.log(graphicData);

    var chart = c3.generate({
        bindto: '#chart',
        data: graphicData
    });
</script>

<?php else : ?>
    <?= callout("info", "Você não é coordenador(a) de nenhum programa. Apenas os coordenadores dos programas podem acessar o Relatório de Produções dos seus respectivos programas.") ?>
<?php endif ; ?>
