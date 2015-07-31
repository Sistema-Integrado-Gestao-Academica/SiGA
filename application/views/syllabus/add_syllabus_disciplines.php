
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

<?php

	displayDisciplinesToSyllabus($syllabusId, $allDisciplines, $courseId);

	echo anchor("syllabus/displayDisciplinesOfSyllabus/{$syllabusId}/{$courseId}","Voltar", "class='btn btn-primary'");

?>