<?php
    $name = array(
        "name" => "user_name",
        "id" => "user_name",
        "type" => "text",
        "class" => "form-control",
        "placeholder" => "Informe o nome da pessoa para pesquisa.",
        "maxlength" => "70"
    );
?>

<div class="row">
  <div class="col-md-4 col-sm-6">
    <h4><i class='fa fa-search-plus'></i> Pesquise aqui usuários específicos para notificar</h4>
    <div class='form-group'>
      <?= form_label("Nome:", "user_name") ?>
      <?= form_input($name) ?>
    </div>

    <br>
    <?php alert(function(){
        echo "Você pode notificar apenas os professores e alunos dos cursos os quais é secretário(a).";
      }); ?>
  </div>
  <div class="col-md-8 col-sm-6">
    <div id="users_to_notify_list" style="height: 300px; overflow-y: scroll;"></div>
    <br>
  </div>
</div>
<br>

<div id="notify_user_modal"></div>