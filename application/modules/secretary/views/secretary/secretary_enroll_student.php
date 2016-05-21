<br>
<h4 align="left"><b>Matricular alunos</b></h4>
<br>
<h5><b>Lista de cursos:</b></h5>
<?php 

	if($courses !== FALSE){

		buildTableDeclaration();

		buildTableHeaders(array(
			'Código',
			'Curso',
			'Tipo',
			'Ações'
		));

    	foreach($courses as $courseData){

    		$courseId = $courseData['id_course'];

    		$this->load->model("program/course_model");
    		$courseType = $this->course_model->getCourseTypeByCourseId($courseId);

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
	    		echo anchor("enrollStudent/{$courseId}","<i class='fa fa-plus-square'>Matricular Aluno</i>", "class='btn btn-primary'");
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