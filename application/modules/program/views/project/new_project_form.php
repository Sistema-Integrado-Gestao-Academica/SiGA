
<?php

    $financing = array(
        "name" => "financing",
        "id" => "financing",
        "type" => "text",
        "maxlength" => 60,
        "class" => "form-control",
        "placeholder" => "Deixe em branco caso não haja financiamento."
    );

    $name = array(
        "name" => "project_name",
        "id" => "project_name",
        "type" => "text",
        "class" => "form-control",
        "maxlength" => 200,
        "required" => "required",
        "placeholder" => "Informe um nome para o projeto."
    );

    $startDate = array(
        "name" => "project_start_date",
        "id" => "project_start_date",
        "type" => "text",
        "placeholder" => "Informe a data inicial do projeto",
        "class" => "form-control",
        "required" => "required"
    );

    $endDate = array(
        "name" => "project_end_date",
        "id" => "project_end_date",
        "type" => "text",
        "placeholder" => "Previsão de fim do projeto, caso haja",
        "class" => "form-control"
    );

    $studyObject = array(
        'name' => 'study_object',
        'id' => 'study_object',
        'placeholder' => 'Objeto de estudo do projeto.',
        'rows' => '5',
        'cols' => '50',
        "class" => "form-control",
        'style' => "resize: none"
    );

    $justification = array(
        'name' => 'justification',
        'id' => 'justification',
        'placeholder' => 'Justificativa do projeto.',
        'rows' => '5',
        'cols' => '50',
        "class" => "form-control",
        'style' => "resize: none"
    );

    $procedures = array(
        'name' => 'procedures',
        'id' => 'procedures',
        'placeholder' => 'Procedimentos do projeto.',
        'rows' => '5',
        'cols' => '50',
        "class" => "form-control",
        'style' => "resize: none"
    );

    $expectedResults = array(
        'name' => 'expected_results',
        'id' => 'expected_results',
        'placeholder' => 'Objetivos e resultados esperados do projeto.',
        'rows' => '5',
        'cols' => '50',
        "class" => "form-control",
        'style' => "resize: none"
    );

    $newProjectBtn = array(
      "id" => "new_project_btn",
      "class" => "btn btn-lg btn-primary btn-block",
      "content" => "Novo projeto",
      "type" => "submit"
    );
?>

<div id="new_project_form" class="container collapse">

<br>
<h3 align="center"><i class="fa fa-plus-square-o"></i> Novo projeto <br>
<small>* Campos obrigatórios</small></h3>
<br>

  <?= form_open("new_project") ?>

    <div class="row">
      <div class="col-md-6 col-sm-6">
        <div class="form-group">
          <?= form_label("Nome do projeto*", "project_name"); ?>
          <?= form_input($name); ?>
          <?= form_error("project_name") ?>
        </div>
      </div>


      <div class="col-md-6 col-sm-6">
        <div class="form-group">
          <?= form_label("Possui Financiamento? Se sim, informe qual:", "financing"); ?>
          <?= form_input($financing); ?>
          <?= form_error("financing") ?>
        </div>
      </div>
    </div>

    <div class="row">

      <div class="col-md-7 col-sm-8">
          <div class="form-group">
            <?= form_label("Programa*", "program") ?>
            <?= form_dropdown("programs", $programs, "", ['class' => "form-control", 'id' => "programs"]) ?>
          </div>
      </div>
    </div>
    <br>
    <h4><i class="fa fa-calendar"></i> Datas do projeto</h4>
    <br>

    <div class="row">
      <div class="col-md-6 col-sm-6">
        <div class="form-group">
          <?= form_label("Data de início*", "project_start_date"); ?>
          <?= form_input($startDate); ?>
          <?= form_error("project_start_date") ?>
        </div>
      </div>

      <div class="col-md-6 col-sm-6">
        <div class="form-group">
          <?= form_label("Previsão de fim", "project_end_date"); ?>
          <?= form_input($endDate); ?>
          <?= form_error("project_end_date") ?>
        </div>
      </div>
    </div>

    <br>
    <h4><i class="fa fa-list-alt"></i> Dados opcionais para o projeto</h4>
    <br>

    <div class="row">
      <div class="col-md-6 col-sm-4">
        <div class="form-group">
          <?= form_label("Objeto de estudo", "study_object"); ?>
          <?= form_textarea($studyObject); ?>
          <?= form_error("study_object") ?>
        </div>
      </div>

      <div class="col-md-6 col-sm-4">
        <div class="form-group">
          <?= form_label("Justificativa", "justification"); ?>
          <?= form_textarea($justification); ?>
          <?= form_error("justification") ?>
        </div>
      </div>
    </div>

    <br>
    <div class="row">
      <div class="col-md-6 col-sm-4">
        <div class="form-group">
          <?= form_label("Procedimentos", "procedures"); ?>
          <?= form_textarea($procedures); ?>
          <?= form_error("procedures") ?>
        </div>
      </div>

      <div class="col-md-6 col-sm-4">
        <div class="form-group">
          <?= form_label("Objetivos e Resultados esperados", "expected_results"); ?>
          <?= form_textarea($expectedResults); ?>
          <?= form_error("expected_results") ?>
        </div>
      </div>
    </div>

    <br>
    <div class="row">
      <div class="col-md-12 col-sm-6">
        <?= form_button($newProjectBtn) ?>
      </div>
    </div>

  <?= form_close() ?>

  <br>
</div>

<script src=<?=base_url("js/project.js")?>></script>