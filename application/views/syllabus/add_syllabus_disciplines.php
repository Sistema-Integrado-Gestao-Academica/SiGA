
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
	<?=anchor("secretary/syllabus/addDisciplines/{$syllabusId}/{$courseId}", "Visualizar todas", "class='btn bg-olive btn-flat'");?>
<?php
	
	buildTableDeclaration();

	buildTableHeaders(array(
		'Código',
		'Sigla',
		'Disciplina',
		'Créditos',
		'Linhas de pesquisa',
		'Ações'
	));

    if($allDisciplines !== FALSE){

	    foreach($allDisciplines as $discipline){

		    $syllabus = new Syllabus();
    		$disciplineAlreadyExistsInSyllabus = $syllabus->disciplineExistsInSyllabus($discipline['discipline_code'], $syllabusId);

    		$disciplineController = new Discipline();
    		$disciplineResearchLinesIds = $disciplineController->getDisciplineResearchLines($discipline['discipline_code']);
    		if ($disciplineResearchLinesIds){
    			$disciplineResearchLinesNames = $syllabus->getDiscipineResearchLinesNames($disciplineResearchLinesIds);
    		}else{
    			$disciplineResearchLinesNames = FALSE;
    		}
		    echo "<tr>";
		    	echo "<td>";
	    			echo $discipline['discipline_code'];
		    	echo "</td>";

		    	echo "<td>";
		    		echo $discipline['name_abbreviation'];
		    	echo "</td>";

		    	echo "<td>";
		    		echo $discipline['discipline_name'];
		    	echo "</td>";

		    	echo "<td>";
		    		echo $discipline['credits'];
		    	echo "</td>";

		    	echo "<td>";
		    		if ($disciplineResearchLinesNames){
		    			foreach ($disciplineResearchLinesNames as $names){
		    				echo $names."<br>";
		    			}
		    		}else{
		    			echo "Não relacionada a nenhuma linha de pesquisa.";
		    		}
		    	echo "</td>";

		    	echo "<td>";
		    		if($disciplineAlreadyExistsInSyllabus){
		    			echo anchor("secretary/syllabus/removeDisciplineFromSyllabus/{$syllabusId}/{$discipline['discipline_code']}/{$courseId}", "Remover disciplina", "class='btn btn-danger'");
		    		}else{
		    			echo anchor("secretary/syllabus/addDisciplineToSyllabus/{$syllabusId}/{$discipline['discipline_code']}/{$courseId}", "Adicionar disciplina", "class='btn btn-primary'");
		    		}
		    	echo "</td>";

		    echo "</tr>";
	    }

    }else{

    	echo "<tr>";
    	echo "<td colspan=5>";
    		callout("warning", "Nenhuma disciplina encontrada.");
    	echo "</td>";
		echo "</tr>";
    }

    buildTableEndDeclaration();
	echo anchor("secretary/syllabus/displayDisciplinesOfSyllabus/{$syllabusId}/{$courseId}","Voltar", "class='btn btn-primary'");

?>