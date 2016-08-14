<h2 class="principal">Disciplinas ofertadas no semestre</h2>

<h4>Cursos para o(a) secretário(a) <b><?php echo $userName?></b>:</h4>
<?php

    if($courses !== FALSE){
        secretaryCoursesTable($courses);
    }else{
?>
    <div class="callout callout-info">
        <h4>Nenhum curso cadastrado no momento para sua secretaria.</h4>
    </div>
<?php }

    echo anchor("secretary_home", "Voltar", "class='btn btn-danger'");
?>