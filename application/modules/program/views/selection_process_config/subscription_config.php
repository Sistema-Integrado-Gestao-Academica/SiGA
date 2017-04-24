<br>
<br>
<h3>
  <i class="fa fa-files-o"></i> Documentos necessários<br>
  <small>
    <p>Configure os documentos necessários para este edital abaixo.</p>
    <p>Os documentos selecionados serão os documentos obrigatórios para o(a) candidato(a) enviar no ato da inscrição.</p>
  </small>
</h3>
<br>

<?php

  $submitBtn = [
    'class' => 'btn btn-primary btn-block',
    'type' => 'submit',
    'content' => "Salvar e finalizar {$btn} do edital"
  ];

  $researchLines = [
    'name' => 'research_lines',
    'id' => 'research_lines',
    'placeholder' => 'Insira as linhas de pesquisa separadas por vírgula',
    'rows' => '20',
    "class" => "form-control",
    'style' => 'height: 70px; margin-top:-10%;'
  ];

  function docCheckbox($doc, $processDocs, $canNotEdit){
    $checkboxId = 'doc_'.$doc['id'];
    $checkbox = [
      'id' => $checkboxId,
      'name' => $checkboxId,
      'value' => $doc['id'],
      'class' => 'form-control',
      'checked' => in_array($doc, $processDocs),
    ];
    
    if($canNotEdit){
      $checkbox['disabled'] = TRUE;
    } 

    echo form_checkbox($checkbox);
    echo form_label($doc['doc_name'], $checkboxId);
    echo '<p>'.$doc['doc_desc'].'</p>';
  }
?>

<?php if ($allDocs !== FALSE): ?>

  <?= form_open("selection_process/config/save_subscription_config/{$process->getId()}") ?>
    <div class="row">
      <?php foreach ($allDocs as $doc): ?>
        <div class="col-md-6">
          <?php docCheckbox($doc, $processDocs, $canNotEdit); ?>
        </div>
      <?php endforeach ?>
    </div>

    <br>

<?php else: ?>
  <?php callout('info', 'Nenhum documento cadastrado.'); ?>
<?php endif ?>

<h3>
  <i class="fa fa-bars"></i> Linhas de Pesquisa<br>
</h3>
  <?php $courseId = $process->getCourse();
  createReseachLineModal($course);
  if(!$canNotEdit){
    echo "<a href='#createReseachLineModal{$courseId}' data-toggle='modal' class='btn bg-olive pull-right'><i class='fa fa-plus'></i>Adicionar Linha de Pesquisa</a>";
  }
?>

<?php if ($courseResearchLines !== FALSE): ?>
  <h3> 
  <small>
    <p>As linhas de pesquisa para escolha do candidato são:</p>
  </small>
  </h3>
<ul>
<?php foreach ($courseResearchLines as $researchLine):  ?>
  <div class="col-md-6" id="research_lines">
    <li> <?= $researchLine['description'] ?> </li>
  </div>
<?php endforeach ?>
</ul>
<br>
<?php else: ?>
  <?php callout('info', 'Nenhuma linha de pesquisa cadastrada para o curso.', FALSE, "callout_research_line"); ?>
  <ul>
    <div class="col-md-6" id="research_lines">
    </div>
  </ul>
<?php endif ?>
<br>
<br>
    <?= form_button($submitBtn) ?>
  <?= form_close() ?>

<br>


<div class="col-sm-2 pull-left">
    <button class='btn btn-danger pull-left' type="button" id="back_to_define_teachers">Voltar</button>
</div>

<?php 
function createReseachLineModal($course){
  $courseArray = array(
    $course['id_course'] => $course['course_name']
  );
  $body = function() use ($courseArray){
     $researchLine = array(
        "name" => "research_line",
        "id" => "research_line",
        "type" => "text",
        "class" => "form-campo",
        "class" => "form-control",
        "maxlength" => "80"
    );

      echo form_label("Linha de Pesquisa", "research_line");
        echo form_input($researchLine);

      echo form_label("Curso da Linha de Pesquisa", "research_course");
      echo "<br>";
      echo form_dropdown("research_course", $courseArray, '', "id='research_course' class ='form-control'");
    };

  $footer = function(){
    echo "<div id='result'>";
    echo "</div>";
    echo "<div class='row'>";
      echo "<div class='col-lg-6'>";
        echo form_button(array(
            "class" => "btn btn-danger btn-block",
            "content" => "Fechar",
            "type" => "button",
            "data-dismiss"=>'modal'
        ));
      echo "</div>";
      echo "<div class='col-lg-6'>";
        echo form_button(array(
            "id" => 'save_research_line',
            "class" => "btn bg-olive btn-block",
            "content" => 'Salvar',
        ));
      echo "</div>";
    echo "</div>";

  };

  $courseId = $course['id_course'];
  newModal("createReseachLineModal{$courseId}", "Criar linha de pesquisa", $body, $footer);
}