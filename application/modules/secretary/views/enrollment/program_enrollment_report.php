<h2 class="principal">Relatório Geral de Matrícula do semestre <?=$semester['description']?></h2>

<?php

if($disciplines !== FALSE && !empty($disciplines)){

    if($students !== FALSE && !empty($disciplines)){
        echo "<div align='right'>";
            echo " <button onclick='collapseAllStudents(\"{$offerDisciplinesIds}\")' type='button' class='btn btn-primary'>Ver todos os alunos</button>";
        echo "</div>";
    }
    foreach($disciplines as $discipline){

        $id = $discipline['discipline_code'];

        echo "<div class='panel panel-success'>";
            echo "<div class='panel-body' id='course_guest_title'>";

                echo $discipline['name_abbreviation']." - ".$discipline['discipline_name'];

            echo "</div>";

            if($disciplinesClasses[$id] !== FALSE){

                foreach ($disciplinesClasses[$id] as $class) {
                    $idOfferDiscipline = $class['id_offer_discipline'];
                    echo "<div class='panel-footer'>";

                            echo "<b>Turma: </b>".$class['class'];
                            echo "<br>";
                            $filledVacancies = count($students[$idOfferDiscipline]);
                            echo "<b>Vagas Ocupadas: </b>".$filledVacancies;
                            echo "<br>";
                            echo "<b>Vagas Disponíveis: </b>".$class['total_vacancies'];
                            echo "<br>";

                            if($filledVacancies !== 0){

                                echo "<div align='right'>";
                                    echo "<a data-toggle='collapse' href='#students{$idOfferDiscipline}' class='collapsed' aria-expanded='false'>";
                                    echo "<i class='fa fa-eye'> Alunos matriculados</i></a>";
                                echo "</div>";
                                echo "<div id='students{$idOfferDiscipline}' class='panel-collapse collapse' aria-expanded='false'>";

                                    echo "<div align='center'>";

                                        echo "<div class='box-body'>";

                                        if($students[$idOfferDiscipline] !== FALSE){

                                            foreach ($students[$idOfferDiscipline] as $student) {

                                                echo $student;
                                                echo "<br>";
                                            }
                                        }

                                        echo "</div>";
                                    echo "</div>";

                                echo "</div>";
                            }
                    echo "</div>";
                }
            }

        echo "</div>";

    }
}
else{
    echo "<div class='callout callout-green'>";
    echo "<h4> Não há alunos matriculados neste semestre</h4>"; 
    echo "</div>";
}

?>

