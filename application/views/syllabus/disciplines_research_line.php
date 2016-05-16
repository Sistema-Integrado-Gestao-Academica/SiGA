<?php
echo "<div class='col-6'>";
	relateDisciplineToResearchLineForm($researchLines, $discipline, $syllabusId, $courseId);
echo "</div>";

echo "<div class='col-6'>";

	echo "<h3>Linhas de pesquisa da disciplina ". $discipline['discipline_name']."</h3>";

	buildTableDeclaration();

	$headers = array('Linha de Pesquisa');
	if($disciplineResearchLines){
		$headers[] = "Ações";
	}

	buildTableHeaders($headers);

	if (!$disciplineResearchLines){
		echo "<tr>";
			echo "<td>";
				echo "Não foi relacionada nenhuma linha de pesquisa";
			echo "</td>";
		echo "</tr>";
	}else{
		foreach ($disciplineResearchLines as $key => $line){
			echo "<tr>";
				echo "<td>";
					echo $line;
				echo "</td>";
				echo "<td>";
				echo anchor("secretary/syllabus/removeDisciplineResearchLine/{$key}/{$discipline['discipline_code']}/{$syllabusId}/{$courseId}", "<i class='fa fa-eraser'></i> Remover Linha de Pesquisa", "class='btn btn-danger'");
				echo "</td>";

			echo "</tr>";
		}
	}

	buildTableEndDeclaration();
	echo "</div>";

	echo "<br><br>";
echo anchor("secretary/syllabus/displayDisciplinesOfSyllabus/{$syllabusId}/{$courseId}","Voltar", "class='btn btn-primary'");
