
<h2 class="principal"> <i class="fa fa-file-text-o"></i> Relatório de avaliações</h2>

<?php
buildTableDeclaration();

buildTableHeaders(array(
    'Cursos',
    'Ações'
));

if($programs  !== FALSE){

    foreach($programs  as $program ){
        echo "<tr>";

            echo "<td>";
                echo $program['program_name'];
            echo "</td>";

            echo "<td>";

                echo anchor(
                    "program/coordinator/programEvaluationsReport/{$program['id_program']}",
                    "<i class='fa fa-list'></i> Relatório do <b>".$program['acronym']."</b>",
                    "class='btn btn-primary'"
                );

            echo "</td>";

        echo "</tr>";
    }

}else{
    echo "<td colspan=2>";
        callout("info", "Você não é coordenador(a) de nenhum programa. Apenas os coordenadores dos programas podem acessar o Relatório de Avaliações dos seus respectivos programas.");
    echo "</td>";
}

buildTableEndDeclaration();

