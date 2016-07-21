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

<h4><i class='fa fa-search-plus'></i> Pesquisa para adicionar novos membros ao projeto</h4>
<div class="row container">
  <div class="col-md-6 col-sm-12">
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


<div id="member_search_result"></div>
<br>

<script src=<?=base_url("js/project.js")?>></script>