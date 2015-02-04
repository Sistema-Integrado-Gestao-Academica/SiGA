<br>
<h3>Adicionar disciplinas a lista de oferta</h3>
<br>
<h4>Lista de disciplinas</h4>

<?php 
	
	displayRegisteredDisciplines($allDisciplines, $course, $idOffer);

	echo "<br>";
	echo anchor("offer/displayDisciplines/{$idOffer}/{$course['id_course']}", "Voltar", "class='btn btn-danger'");
?>
