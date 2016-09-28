
<h2 class="principal">Atualizar solicitação de matrícula</h2>

<?php
  if(!$request['secretary_approval'] && ($request['current_role'] === EnrollmentConstants::REQUEST_TO_STUDENT || (!$request['mastermind_approval'] && EnrollmentConstants::REQUEST_TO_MASTERMIND) )){
?>
    <div class="alert alert-info alert-dismissible" role="alert">
      <i class="fa fa-info"></i>
      <h3 class="text-center"><b>INFORMAÇÕES IMPORTANTES!</b></h3>
      <h4 class="text-left">
      <p>- A sua solicitação original não pode ser alterada até que o seu orientador e/ou secretaria do seu curso responda sua solicitação.</p>
      <br>
      <p>- O campo <b>'Solicitado depois'</b> informa se a disciplina foi solicitada após a confirmação da solicitação de matrícula ou não. A solicitação original é composta pelas disciplinas marcadas como não solicitadas depois.</p>
      <br>
      <p>- Disciplinas já aprovadas <b>NÃO</b> podem ser retiradas pelo aluno.</p>
      <br>
      <p>- Caso alguma disciplina for recusada pelo orientador e/ou secretaria, você pode removê-la e adicionar outra disciplina.</p>
      <br>
      <p>- Quando terminar de alterar sua solicitação <b>clique em 'Reenviar Solicitação'</b> para enviar a solicitação para o orientador e/ou secretaria.</p>
      </h4>
    </div>

<?php

    $resendBtn = function($request){
      echo anchor(
        "resend_request/{$request['id_request']}",
        "Reenviar solicitação",
        "id='resend_request_btn' class='btn btn-primary btn-block btn-lg'"
      );
    };

    displayDisciplinesToRequest($disciplines, $courseId, $userId, $semester['id_semester'], TRUE);

    $resendBtn($request);

    echo form_input(array(
        "id" => "is_update",
        "name" => "is_update",
        "type" => "hidden",
        "value" => TRUE
    ));

    echo form_input(array(
        "id" => "request",
        "name" => "request",
        "type" => "hidden",
        "value" => $request['id_request']
    ));

    include('search_discipline_form.php');
}else{
?>

  <div class="alert alert-info" role="alert">
    <i class="fa fa-info"></i>
    <?php if($request['secretary_approval']): ?>
      <h4 class="text-center">Solicitação já finalizada pela secretaria.
      <p><small>Não é possível atualizar solicitações finalizadas.</small></p></h4>
    <?php else: ?>
      <h4 class="text-center">Sua solicitação está sendo avaliada pela secretaria.
      <p><small>Não é possível atualizar solicitações em análise pela secretaria.</small></p></h4>
    <?php endif; ?>
  </div>

<?php } ?>
