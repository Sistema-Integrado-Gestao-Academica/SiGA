<?php
echo "<div class='col-6'>";
	relateDisciplineToResearchLineForm($researchLines, $discipline, $syllabusId, $courseId);
echo "</div>";

echo "<div class='col-6'>";
	displayDisciplineToResearchLineTable($disciplineResearchLines, $discipline, $syllabusId, $courseId);
echo "</div>";

echo "<br><br>";
echo anchor("syllabus/displayDisciplinesOfSyllabus/{$syllabusId}/{$courseId}","Voltar", "class='btn btn-primary'");
