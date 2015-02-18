
<br>
<br>

<h3>Adicionar disciplinas ao currículo</h3>
<br>

<?php

	displayDisciplinesToSyllabus($syllabusId, $allDisciplines);

	echo anchor("syllabus/displayDisciplinesOfSyllabus/{$syllabusId}/{$courseId}","Voltar", "class='btn btn-primary'");

?>