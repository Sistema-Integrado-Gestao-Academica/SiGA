
<br>
<h3 align="left">Currículo do curso <b><?php echo $course['course_name']?></b></h3>
<h4>Código do currículo: <b><?php echo $syllabusId; ?></b></h4>
<br>

<?php 

	$courseId = $course['id_course']; 
	
	echo anchor("secretary/syllabus/addDisciplines/{$syllabusId}/{$courseId}", "<i class='fa fa-plus-circle'></i> Adicionar disciplinas", "class='btn-lg'");

	buildTableDeclaration();

	buildTableHeaders(array(
		'Disciplina',
		'Linhas de pesquisa',
		'Ações'
	));

    if($syllabusDisciplines !== FALSE){

    	foreach($syllabusDisciplines as $discipline){
    		$disciplineController = new Discipline();
    		$disciplineResearchLinesIds = $disciplineController->getDisciplineResearchLines($discipline['discipline_code']);

    		$syllabus = new Syllabus();
    		$disciplineResearchLinesNames = $syllabus->getDiscipineResearchLinesNames($disciplineResearchLinesIds);

	    	echo "<tr>";
		    	echo "<td>";
		    		echo $discipline['discipline_code']." - ".$discipline['discipline_name']." (".$discipline['name_abbreviation'].")";
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
		    	echo anchor("secretary/syllabus/relateDisciplineToResearchLine/{$discipline['discipline_code']}/{$syllabusId}/{$courseId}", "Relacionar Linha de Pesquisa", "class='btn btn-success'");
		    	echo "</td>";
	    	echo "</tr>";
    	}
    }else{

    	echo "<tr>";
    		echo "<td colspan=3>";
			   	$content = anchor("secretary/syllabus/addDisciplines/{$syllabusId}/{$courseId}", "Adicionar disciplinas", "class='btn btn-primary'");
				$principalMessage = "Nenhuma disciplina adicionada ao currículo.";
    			$callout = wrapperCallout("info", $content, $principalMessage);
    			$callout->draw();
    		echo "</td>";
    	echo "</tr>";
    }

    buildTableEndDeclaration();
	echo anchor('secretary/syllabus/secretaryCourseSyllabus',"Voltar", "class='btn btn-primary'");

?>