<br>
<h2 class="principal"><b>Lista de alunos</b></h2>
<br>
<h4><b>Lista de cursos:</b></h4>
<?php 

	if($courses !== FALSE){
?>
		<div class="box-body table-responsive no-padding">
		<table class="table table-bordered table-hover">
			<tbody>
				<tr>
			        <th class="text-center">Código</th>
			        <th class="text-center">Curso</th>
			        <th class="text-center">Tipo</th>
			        <th class="text-center">Ações</th>
			    </tr>
<?php
			    	foreach($courses as $courseData){

			    		$courseId = $courseData['id_course'];

						echo "<tr>";
				    		echo "<td>";
				    		echo $courseId;
				    		echo "</td>";

				    		echo "<td>";
				    		echo $courseData['course_name'];
				    		echo "</td>";

				    		echo "<td>";
				    		echo $courseData['type'];
				    		echo "</td>";

				    		echo "<td>";
				    		echo anchor("program/course/courseStudents/{$courseId}","<i class='fa fa-list'> Lista de alunos</i>", "class='btn btn-primary'");
				    		echo "</td>";
			    		echo "</tr>";
			    	}
?>			    
			</tbody>
		</table>
		</div>

<?php
 	} else{
?>
	<div class="callout callout-info">
		<h4>Nenhum curso cadastrado no momento para sua secretaria.</h4>
	</div>
<?php }?>

<?= anchor('secretary_home', 'Voltar', "class='btn btn-danger'")?>