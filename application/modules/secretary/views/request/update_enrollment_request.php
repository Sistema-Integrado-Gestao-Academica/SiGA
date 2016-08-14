<h2 class="principal">Atualizar solicitação de matrícula</h2>


<?php
if(!$request['secretary_approval']){
?>
    <div class="alert alert-info alert-dismissible" role="alert">
      <i class="fa fa-info"></i>
      <h4 class="text-center">A sua solicitação original não pode ser alterada até que o seu orientador e/ou secretaria do seu curso responda sua solicitação.
      <br>
      <br>
      <p>Caso alguma disciplina for recusada pelo orientador e/ou secretaria, você pode removê-la e adicionar outra disciplina.</p></h4>
    </div>

<?php
    displayDisciplinesToRequest($disciplines, $courseId, $userId, $semester['id_semester'], TRUE);

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
  <h4 class="text-center">Solicitação já finalizada pela secretaria.
    <p><small>Não é possível atualizar solicitações finalizadas.</small></p></h4>
</div>

<?php } ?>
