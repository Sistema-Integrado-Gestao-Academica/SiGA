<br>
<h4 align="left"><b>Cadastrar Orientadores</b></h4>
<br>
<h5><b>Lista de cursos:</b></h5>
<?php 

	if($courses !== FALSE){
		$courseController = new Course();

		buildTableDeclaration();

		buildTableHeaders(array(
			'Código',
			'Curso',
			'Tipo',
			'Ações'
		));

		foreach($courses as $courseData){

			$courseId = $courseData['id_course'];
			$courseType = $courseController->getCourseTypeByCourseId($courseId);

			echo "<tr>";
				echo "<td>";
				echo $courseId;
				echo "</td>";

				echo "<td>";
				echo $courseData['course_name'];
				echo "</td>";

				echo "<td>";
				echo $courseType['description'];
				echo "</td>";

				echo "<td>";
				echo anchor("checkMastermind/{$courseId}","<i class='fa fa-plus-square'>Checar Orientadores do Curso</i>", "class='btn btn-primary'");
				echo "</td>";
			echo "</tr>";
		}

		buildTableEndDeclaration();
 	} 
 	else{
?>
	<div class="callout callout-info">
		<h4>Nenhum curso cadastrado no momento para sua secretaria.</h4>
	</div>
<?php }?>