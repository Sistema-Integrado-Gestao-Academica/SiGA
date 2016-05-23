<br>
<h4 align="center"><b>Currículos de cursos</b></h4>
<br>

<b>Semestre atual</b>
<h4><?=$current_semester['description']?></h4>
<br>

<?php 

	$userName = $user->getName();
	if($courses !== FALSE){
		
		echo "<h4>Cursos para o secretário <b>".$userName."</b>:</h4>";

		$course = new Course();
		buildTableDeclaration();

		buildTableHeaders(array(
			'Curso',
			'Código Currículo',
			'Ações'
		));

	    if($syllabus !== FALSE){

		    foreach($syllabus as $courseName => $syllabus){

		    	$foundCourse = $course->getCourseByName($courseName);
				$courseId = $foundCourse['id_course'];

		    	echo "<tr>";

		    		echo "<td>";
		    			echo $courseName;
		    		echo "</td>";

		    		if($syllabus !== FALSE){

		    			echo "<td>";
		    				echo $syllabus['id_syllabus'];
		    			echo "</td>";

		    			echo "<td>";
	    					$content = anchor("secretary/syllabus/displayDisciplinesOfSyllabus/{$syllabus['id_syllabus']}/{$courseId}","<i class='fa fa-edit'></i>", "class='btn btn-danger'");
	    					$principalMessage = "Editar";
	    					$aditionalMessage = "<b><i>Aqui é possível adicionar e retirar disciplinas ao currículo do curso.</i><b/>";
	    					$callout = wrapperCallout("info", $content, $principalMessage, $aditionalMessage);
	    					$callout->draw();
		    			echo "</td>";

		    		}else{
						echo "<td colspan=2>";
					    	$content = anchor("secretary/syllabus/newSyllabus/{$courseId}", "Novo Currículo", "class='btn btn-primary'");
							$principalMessage = "Nenhum currículo cadastrado para esse curso.";
	    					$callout = wrapperCallout("info", $content, $principalMessage);
	    					$callout->draw();
		    			echo "</td>";
		    		}

		    	echo "</tr>";
		    }
	    }else{
			echo "<td colspan=3>";
				callout("info", "Nenhum curso cadastrado para este secretário.");
			echo "</td>";
	    }

		buildTableEndDeclaration();

	}else{
?>
		<div class="callout callout-warning">
            <h4>Nenhum curso cadastrado para o secretário <b><?php echo $userName;?></b>.<br><br>
            <small><b>OBS.: Você somente pode criar e alterar currículos dos cursos os quais é secretário.</b></small></h4>
        </div>

<?php } ?>

<?php echo anchor('secretary_home', "Voltar", "class='btn btn-primary'"); ?>
