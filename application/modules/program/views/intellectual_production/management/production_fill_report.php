<h2 class="principal">Relatório de Preenchimento de Produções</h2>

<?= alert(function(){
        $alert = "<h4><p>Este relatório apresenta os <b>discentes</b> e <b>docentes</b>, dos cursos os quais você é coordenador(a) ou secretário(a), que já possuem <b>produções cadastradas</b> no ano em questão.</p></h4>";
        echo $alert;
    }, "info");
?>


<?php

$formInput = array(
    "name" => "report_year",
    "id" => "report_year",
    "type" => "number",
    "placeholder" => "Informe um ano para a pesquisa",
    "class" => "form-control",
    "step" => 1,
    "max" => $currentYear,
    "min" => 2000, // Year 2000 at min
    "value" => $searchYear
);

$submitBtn = array(
    "id" => "update_report_year",
    "name" => "update_report_year",
    "class" => "btn btn-success",
    "content" => "<i class='fa fa-refresh'></i> Atualizar ano",
    "type" => "submit"
);
?>

<?= form_open('productions_fill_report', ['method' => 'get', 'id' => 'update_report_form']) ?>
    <div class='row'>
        <div class='col-md-6 col-md-offset-3'>
            <div class='input-group'>
                <?= form_input($formInput) ?>
                <span class='input-group-btn'>
                <?= form_button($submitBtn) ?>
                </span>
            </div>
        </div>
    </div>

    <br>
    <div class='row'>
        <div class='col-md-6 col-md-offset-3'>
            <div class='input-group'>
                <label class="btn btn-default" id="only_registered_productions">
                    <?= form_checkbox(array(
                        'name' => 'only_registered_productions',
                        'id' => 'only_registered_productions',
                        'value' => TRUE,
                        'checked' => $filled,
                        'class' => 'form-control'
                    )) ?>
                    Mostrar usuários que possuem produções cadastradas.
                </label>
            </div>
        </div>
    </div>
<?= form_close() ?>

<br>
<h3 class="text-center">Ano de referência: <b><?= $referenceYear ?></b></h3>

<div class="row">
    <div class="col-md-6">
        <h4 class="text-center"> <i class="fa fa-book"></i>
            Discentes (<b><?= !empty($students) ? count($students) : 0 ?></b>) :
        </h4>
        <?php showUsers($students, $filled, $searchYear); ?>
    </div>
    <div class="col-md-6">
        <h4 class="text-center"> <i class="fa fa-pencil-square-o"></i>
            Docentes (<b><?= !empty($teachers) ? count($teachers) : 0 ?></b>):
        </h4>
        <?php showUsers($teachers, $filled, $searchYear); ?>
    </div>
</div>

<script>
    $(document).ready(function(){
        $("#only_registered_productions").click(function(){
            $("#update_report_form").trigger('submit');
        });
    });
</script>

<style type="text/css">
    #users_with_productions{
        height: 400px;
        overflow-y: auto;
    }
</style>

<?php
function showUsers($users, $filled, $year){
    searchUsersForm();

    buildTableDeclaration('users_with_productions');

    buildTableHeaders([
        'Nome',
        'E-mail',
        'Curso',
    ]);

    if(!empty($users)){

        foreach ($users as $user) {
            echo "<tr>";
                echo "<td>";
                echo "<a data-toggle='modal' href='#user_productions_{$user['id']}'>
                    <i class='fa fa-book'></i> " . $user['name'] . "</a>";
                echo "</td>";

                echo "<td>";
                echo $user['email'];
                echo "</td>";

                echo "<td>";
                echo $user['course_name'];
                echo "</td>";

            echo "</tr>";

            newModal(
                "user_productions_{$user['id']}",
                "Produções de {$user['name']}",
                function() use ($user, $year) {
                    showUserProductions($user, $year);
                },
                function(){}
            );
        }
    }else{
        echo "<tr>";
            echo "<td colspan='3'>";

                $msg = $filled
                    ? "Nenhum usuário cadastrou produções neste ano ou você não é coordenador(a) de nenhum programa."
                    : "Todos os usuários cadastraram produções neste ano ou você não é coordenador(a) de nenhum programa.";
                callout("info", $msg);
            echo "</td>";
        echo "</tr>";
    }

    buildTableEndDeclaration();
}

function searchUsersForm(){

}

function showUserProductions($user, $year){
    $ci =& get_instance();
    $ci->load->model("program/production_model");
    $productions = $ci->production_model->getUserProductions($user['id'], $year);

    echo "<h4> <i class='fa fa-book'></i> Produções de <b><i>{$user['name']}</b></i> em <b><i>{$year}</b></i>:</h4><br>";
    if(!empty($productions)){
        echo "<div class='panel-group' id='accordion'>";
            foreach ($productions as $production) {
                echo "<div class='panel panel-default'>";
                    echo "<div class='panel-heading'>";
                        echo "<h4 class='panel-title'>";
                        echo "<a data-toggle='collapse' data-parent='#accordion' href='#production_{$production->getId()}'>";
                            echo "<i class='fa fa-chevron-down'></i> " . $production->getTitle();
                        echo "</a>";
                        echo "</h4>";
                    echo "</div>";

                    echo "<div id='production_{$production->getId()}' class='panel-collapse collapse'>";
                        echo "<div class='panel-body'>";
                            echo bold("Título: ") . $production->getTitle();
                            echo "<br>";
                            echo bold("Periódico: ") . $production->getPeriodic();
                            echo "<br>";
                            echo bold("Ano: ") . $production->getYear();
                            echo "<br>";
                            echo bold("Identificador: ") . $production->getIdentifier();
                            echo "<br>";
                            echo bold("Qualis: ") . $production->getQualis();
                        echo "</div>";
                    echo "</div>";
                echo "</div>";
            }
        echo "</div>";
    }else{
        callout("info", "Este usuário não possui produções cadastradas.");
    }
}
?>