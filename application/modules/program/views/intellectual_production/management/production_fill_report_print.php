
<?php
    $msg = $filled ? "" : "não";
?>
<h2 class="principal">Relatório de preenchimento de produções do ano de <?= $year ?></h2>

<h4><i class='fa fa-list'></i> Discentes que <?= $msg ?> produziram no ano de <?= $year ?>:</h4>
<?php listUserProductions($users->students, $filled, $year); ?>

<h4><i class='fa fa-list'></i> Docentes que <?= $msg ?> produziram no ano de <?= $year ?>:</h4>
<?php listUserProductions($users->teachers, $filled, $year); ?>

<?php
    function listUserProductions($users, $filled, $year){

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
            }
        }else{
            callout("info", "Nenhum dado encontrado.");
        }

        buildTableEndDeclaration();
    }
?>

<script>
    $(document).ready(function(){
        window.print();
    });
</script>