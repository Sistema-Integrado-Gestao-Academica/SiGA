<?php
echo "<div class='col-lg-6'>";
	relateDisciplineToResearchLineForm($researchLines, $discipline, $syllabusId, $courseId);
echo "</div>";

echo "<div class='col-lg-6'>";
	//displayDisciplineToResearchLineTable($researchLines, $discipline);
echo "</div>";

echo "<br><br>";
echo anchor("syllabus/displayDisciplinesOfSyllabus/{$syllabusId}/{$courseId}","Voltar", "class='btn btn-primary'");
