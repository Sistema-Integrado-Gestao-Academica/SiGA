<?php
    $name = array(
        "name" => "member_name",
        "id" => "member_name",
        "type" => "text",
        "class" => "form-control",
        "placeholder" => "Informe o nome ou CPF da pessoa para pesquisa.",
        "maxlength" => "70"
    );
?>

<div class="container">
  <div class="row">
    <div class="col-md-8 col-sm-6">
      <h4><i class='fa fa-search-plus'></i> Pesquisa para adicionar novos membros ao projeto</h4>
      <div class='form-group'>
        <?= form_input(array(
              "id" => "project_id",
              "type" => "hidden",
              "value" => $project['id']
            )) ?>
        <?= form_label("Nome ou CPF:", "member_name") ?>
        <?= form_input($name) ?>
      </div>
    </div>
  </div>

  <br>
  <div class="row">
    <div class="alert alert-info alert-dismissible" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <i class="fa fa-info"></i>
      <h5 class="text-center">Somente <b>professores</b> e <b>estudantes</b> podem ser adicionados a uma equipe de projeto.</h5>
    </div>

    <div id="member_search_result"></div>
    <br>
  </div>
</div>

<script src=<?=base_url("js/project.js")?>></script>