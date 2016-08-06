<br>
<h2 class="principal">Solicitações de matrícula do curso <b><i><?php echo $course['course_name'];?></i></b></h2>


<div class="alert alert-info alert-dismissible" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <i class="fa fa-info"></i>
  <h3 class="text-center"><p><b>INFORMAÇÕES IMPORTANTES</b></p></h3><br>

  <h4> <p>- Ao clicar em <b>Finalizar solicitaçao NÃO </b> será mais possível recusar ou aprovar disciplinas.</p>
  <p> - Após a solicitação finalizada, o aluno também <b>NÃO</b> poderá mais editar sua solicitação. Portanto, apenas finalize a solicitação quando o aluno não puder fazer mais alterações em sua solicitação.</p>
  <p> - <b>Somente</b> após a finalização da solicitação as vagas serão computadas e atualizadas.</p>
  </h4>
</div>

<?php
	displayCourseRequests($requests, $course['id_course'], $users);

	echo anchor("secretary/requestReport", "Voltar", "class='btn btn-danger'");
?>