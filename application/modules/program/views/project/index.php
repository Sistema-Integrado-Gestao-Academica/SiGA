<h2 class="principal">Projetos participantes</h2>

<h4><i class="fa fa-list"></i> Veja os projetos que você participa:</h4>
<?php

buildTableDeclaration();

buildTableHeaders(array(
    'Projeto',
    'Ações'
));

if($projects !== FALSE){

    foreach($projects as $project){
        echo "<tr>";

            echo "<td>";
                echo $project['name'];
            echo "</td>";

            echo "<td>";

                echo anchor(
                    "program/selectiveprocess/programCourses/{$program['id_program']}",
                    "<i class='fa fa-group'></i> Equipe",
                    "class='btn btn-warning'"
                );

            echo "</td>";

        echo "</tr>";
    }

}else{
    echo "<tr>";
        echo "<td colspan=2>";
            callout("info", "Você não coordena ou não faz parte de nenhum projeto no momento.");
        echo "</td>";
    echo "</tr>";
}

buildTableEndDeclaration();