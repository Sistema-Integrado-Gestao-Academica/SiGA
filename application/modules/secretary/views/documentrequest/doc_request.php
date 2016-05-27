<h2 class="principal">Solicitações de documentos</h2>

<?php $userName = $userData->getName(); ?>

<h4>Cursos para o(a) secretário(a) <b><?php echo $userName ?></b>:</h4>

<?php if($courses !== FALSE){ 
	
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
			    		echo anchor(
			    			"secretary_doc_requests/{$courseId}",
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