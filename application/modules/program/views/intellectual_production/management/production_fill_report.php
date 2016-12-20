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
    "min" => 2000 // Year 2000 at min
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
        <?php showUsers($students, $filled); ?>
    </div>
    <div class="col-md-6">
        <h4 class="text-center"> <i class="fa fa-pencil-square-o"></i>
            Docentes (<b><?= !empty($teachers) ? count($teachers) : 0 ?></b>):
        </h4>
        <?php showUsers($teachers, $filled); ?>
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
function showUsers($users, $filled){
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
                echo $user['name'];
                echo "</td>";

                echo "<td>";
                echo $user['email'];
                echo "</td>";

                echo "<td>";
                echo $user['course_name'];
                echo "</td>";

            echo "</tr>";
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
?>