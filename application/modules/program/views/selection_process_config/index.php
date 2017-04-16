<h2 class="principal">Configurações do edital <b><i><?= $process->getName(); ?></i></b></h2>

<?= anchor(
    "program/selectiveprocess/courseSelectiveProcesses/{$process->getCourse()}",
    "Voltar",
    "class='pull-right btn btn-danger btn-lg'"
  ); ?>

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
    'class' => 'btn bg-olive btn-block',
    'type' => 'submit',
    'content' => "<i class='fa fa-save'></i> Salvar documentos"
  ];

  function docCheckbox($doc, $processDocs){
    $checkboxId = 'doc_'.$doc['id'];
    $checkbox = [
      'id' => $checkboxId,
      'name' => $checkboxId,
      'value' => $doc['id'],
      'class' => 'form-control',
      'checked' => in_array($doc, $processDocs)
    ];
    echo form_checkbox($checkbox);
    echo form_label($doc['doc_name'], $checkboxId);
    echo '<p>'.$doc['doc_desc'].'</p>';
  }
?>

<?php if ($allDocs !== FALSE): ?>

  <?= form_open("selection_process/config/save_docs/{$process->getId()}") ?>
    <div class="row">
      <?php foreach ($allDocs as $doc): ?>
        <div class="col-md-6">
          <?php docCheckbox($doc, $processDocs); ?>
        </div>
      <?php endforeach ?>
    </div>

    <br>
    <?= form_button($submitBtn) ?>
  <?= form_close() ?>

<?php else: ?>
  <?php callout('info', 'Nenhum documento cadastrado.'); ?>
<?php endif ?>

<br>
<?= anchor(
    "program/selectiveprocess/courseSelectiveProcesses/{$process->getCourse()}",
    "Voltar",
    "class='pull-left btn btn-danger btn-lg'"
  ); ?>