<br>
<h4 align="left"><b>Matricular alunos</b></h4>
<br>
<h5><b>Lista de cursos:</b></h5>
<?php 

	if($courses !== FALSE){
		// On tables helper
		courseTableToSecretaryPage($courses);
 	} else{
?>
	<div class="callout callout-info">
		<h4>Nenhum curso cadastrado no momento para sua secretaria.</h4>
	</div>
<?php }?>