
<?php
    $msg = $filled ? "" : "não";
?>
<h2 class="principal">Relatório de preenchimento de produções do ano de <?= $year ?></h2>

<h4><i class='fa fa-list'></i> Discentes que <?= $msg ?> produziram no ano de <?= $year ?>:</h4>
<?php listUsers($users->students, $year); ?>

<h4><i class='fa fa-list'></i> Docentes que <?= $msg ?> produziram no ano de <?= $year ?>:</h4>
<?php listUsers($users->teachers, $year); ?>

<?php
    function listUsers($users, $year){

        buildTableDeclaration();

        buildTableHeaders([
            'Nome',
            'E-mail',
            'Telefone residencial',
            'Telefone celular',
            'Curso',
        ]);

        if(!empty($users)){
            foreach ($users as $user) {
                echo "<tr>";
                    echo "<td>";
                    echo $user->name;
                    echo "</td>";

                    echo "<td>";
                    echo $user->email;
                    echo "</td>";

                    echo "<td>";
                    echo $user->home_phone;
                    echo "</td>";

                    echo "<td>";
                    echo $user->cell_phone;
                    echo "</td>";

                    echo "<td>";
                    echo $user->course_name;
                    echo "</td>";
                echo "</tr>";

                echo "<tr>";
                    echo "<td colspan='4'>";
                        echo "<h4><i class='fa fa-book'></i>Produções de {$user->name}</h4>";
                        listUserProductions($user, $year);
                    echo "</td>";
                echo "</tr>";
            }
        }else{
            callout("info", "Nenhum dado encontrado.");
        }

        buildTableEndDeclaration();
    }

    function listUserProductions($user, $year){

        $ci =& get_instance();
        $ci->load->model("program/production_model");
        $productions = $ci->production_model->getUserProductions($user->id, $year);

        buildTableDeclaration();
        buildTableHeaders([
            'Título',
            'Ano',
            'Periódico',
            'Qualis',
            'Identificador',
        ]);

        if(!empty($productions)){
            foreach ($productions as $production) {
                echo "<tr>";
                    echo "<td>";
                    echo $production->getTitle();
                    echo "</td>";

                    echo "<td>";
                    echo $production->getYear();
                    echo "</td>";

                    echo "<td>";
                    echo $production->getPeriodic();
                    echo "</td>";

                    echo "<td>";
                    echo $production->getQualis();
                    echo "</td>";

                    echo "<td>";
                    echo $production->getIdentifier();
                    echo "</td>";
                echo "</tr>";
            }
        }else{
            callout("info", "Nenhuma produção encontrada para este usuário.");
        }

        buildTableEndDeclaration();
    }
?>

<script>
    $(document).ready(function(){
        window.print();
    });
</script>