<br>
<h4 align="left"><b>Visualizar relatório de solicitações de matrícula</b></h4>
<br>
<h5><b>Lista de cursos:</b></h5>
<?php 

	if($courses !== FALSE){
		secretaryCoursesToRequestReport($courses);
 	}else{
?>
	<div class="callout callout-info">
		<h4>Nenhum curso cadastrado no momento para sua secretaria.</h4>
	</div>
<?php }

	echo anchor("secretary_home", "Voltar", "class='btn btn-danger'");
?>