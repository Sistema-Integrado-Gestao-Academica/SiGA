
<?php
    $msg = $filled ? "" : "não";
?>
<h2 class="principal">Relatório de preenchimento de produções do ano de <?= $year ?></h2>

<h4><i class='fa fa-list'></i> <b>Discentes</b> que <?= $msg ?> produziram no ano de <?= $year ?>:</h4>
<?php listUsers($users->students, $year); ?>

<h4><i class='fa fa-list'></i> <b>Docentes</b> que <?= $msg ?> produziram no ano de <?= $year ?>:</h4>
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
                        echo "<h4><i class='fa fa-book'></i> Produções de {$user->name}</h4>";
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

        if(!empty($productions)){
            foreach ($productions as $production) {
                echo "<ul class='list-unstyled text-left'>";
                    echo "<li>";
                    echo bold("Título: ").$production->getTitle();
                    echo "</li>";

                    echo "<li>";
                    echo bold("Ano: ").$production->getYear();
                    echo "</li>";

                    echo "<li>";
                    echo bold("Periódico: ").$production->getPeriodic();
                    echo "</li>";

                    echo "<li>";
                    echo bold("Qualis: ").$production->getQualis();
                    echo "</li>";

                    echo "<li>";
                    echo bold("Identificador: ").$production->getIdentifier();
                    echo "</li>";
                echo "</ul>";
                echo "<hr>";
            }
        }else{
            callout("info", "Nenhuma produção encontrada para este usuário.");
        }
    }
?>

<script>
    $(document).ready(function(){
        window.print();
    });
</script>