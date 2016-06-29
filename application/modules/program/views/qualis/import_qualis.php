<script src=<?=base_url("js/qualis.js")?>></script>

<?php
$qualisFile = array(
    "name" => "qualis_file",
    "id" => "qualis_file",
    "type" => "file",
    "required" => TRUE,
    "class" => "filestyle",
    "data-buttonBefore" => "true",
    "data-buttonText" => "Selecione o arquivo CSV",
    "data-placeholder" => "Nenhum CSV selecionado.",
    "data-iconName" => "fa fa-file-text",
    "data-buttonName" => "btn-primary"
);

$submitFileBtn = array(
    "id" => "upload_qualis_btn",
    "class" => "btn btn-success btn-lg btn-block",
    "content" => "Importar dados",
    "type" => "submit"
);
?>

<h2 class="principal"><i class="fa fa-upload"></i> Importar dados dos periódicos</h2>

<!-- Display the not saved periodics -->
<?php
  if(!empty($notSavedPeriodics)){
?>
    <h4>Os periódicos listados abaixo não foram salvos porque possuíam o ISSN inválido ou repetido: </h4>
    <div id="not_saved_periodics" class='row'>
      <ul>
<?php
      foreach ($notSavedPeriodics as $periodic){
?>
          <li> <?= $periodic[ImportQualis_model::ISSN_COLUMN]." => ".$periodic[ImportQualis_model::PERIODIC_COLUMN]?> </li>

<?php
      }
?>
      </ul>
    </div>
<?php } ?>

<br>
<br>

<div class="alert alert-info alert-dismissible" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <i class="fa fa-info"></i>
  <h4>
    Os dados dos periódicos vêm em um arquivo <i><b>'.xls'</b></i>, mas para que o processo de importação dos dados seja mais rápido, salve esse arquivo com a extensão <i><b>'.csv'</b></i> para submetê-lo aqui.
  </h4>
  <p>
    Para realizar essa tarefa basta abrir a planilha no seu editor preferido (como o Excel, por exemplo) e ir em 'Salvar como' e selecionar a opção '.csv'.
  </p>
</div>
<br>

<!-- Upload periodics form -->
<div class="row">
  <h3><i class="fa fa-file-text"></i> Selecione o arquivo .CSV contendo os dados dos periódicos
    <p><small>* Máximo de <?=$maxSize?> KBs.</small>
  </h3>
</div>
<br>

<?= form_open_multipart("upload_qualis"); ?>
  <div class="row"">
    <div class="col-md-8 col-sm-4">
      <?= form_input($qualisFile); ?>
    </div>
  </div>

  <br>

  <div class="row">
    <div class="col-md-8 col-sm-4">
      <?= form_button($submitFileBtn) ?>
    </div>
  </div>

<?= form_close(); ?>

