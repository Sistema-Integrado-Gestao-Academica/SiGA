<br>
<h4 align="left"><b>Matricular alunos</b></h4>
<br>
<h5><b>Lista de cursos:</b></h5>
<?php 
	if (sizeof($courses) > 0){
		// On tables helper
		courseTableToSecretaryPage($courses, $masterDegrees, $doctorates);
 	} else{
?>
	<div class="callout callout-info">
		<h4>Nenhum curso cadastrado no momento para sua secretária.</h4>
	</div>
<?php }?>