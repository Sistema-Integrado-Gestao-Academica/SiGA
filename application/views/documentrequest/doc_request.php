<h2 class="principal">Solicitações de documentos</h2>
<h4>Cursos para o(a) secretário(a) <b><?php echo $userData['name']?></b>:</h4>

<?php if($courses !== FALSE){ 
	
	$courseController = new Course();
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
			    		echo anchor(
			    			"documentrequest/documentRequestReport/{$courseId}",
			    			"<i class='fa fa-list'></i> Visualizar Solicitações",
			    			"class='btn-lg'"
			    		);
			    		echo "</td>";
		    		echo "</tr>";	
		    	}
?>		    
		</tbody>
	</table>
	</div>

<?php }else{ ?>

	<div class="callout callout-info">
		<h4>Nenhum curso cadastrado no momento para sua secretaria.</h4>
	</div>

<?php } ?>

<br>
<?= anchor("secretary_home", "Voltar", "class='btn btn-danger'") ?>