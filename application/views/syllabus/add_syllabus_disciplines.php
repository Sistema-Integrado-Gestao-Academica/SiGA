
<h2 class='principal'>Adicionar disciplinas ao currículo</h2>

<div class='row'>
	<div class='col-md-6'>
	<?php searchForDisciplineByIdForm($syllabusId, $courseId); ?>
	</div>

	<div class='col-md-6'>
	<?php searchForDisciplineByNameForm($syllabusId, $courseId); ?>
	</div>
</div>

<br>
<br>

	<h4>Lista de disciplinas:</h4>
	<?=anchor("syllabus/addDisciplines/{$syllabusId}/{$courseId}", "Visualizar todas", "class='btn bg-olive btn-flat'");?>
<?php
	

	displayDisciplinesToSyllabus($syllabusId, $allDisciplines, $courseId);

	echo anchor("syllabus/displayDisciplinesOfSyllabus/{$syllabusId}/{$courseId}","Voltar", "class='btn btn-primary'");

?>