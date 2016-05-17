<?php
?>

<h2 class="principal">Docentes dos cursos</h2>

<h4>Cursos para o(a) secretário(a) <b><i><?php echo $user->getName()?></i></b>:</h4>
<?php if($courses !== FALSE){ ?>

		<div class="box-body table-responsive no-padding">
		<table class="table table-bordered table-hover">
			<tbody>
				<tr>
			        <th class="text-center">Código</th>
			        <th class="text-center">Curso</th>
			        <th class="text-center">Ações</th>
			    </tr>
<?php
			    	foreach($courses as $course){

						echo "<tr>";
				    		echo "<td>";
				    		echo $course['id_course'];
				    		echo "</td>";

				    		echo "<td>";
				    		echo $course['course_name'];
				    		echo "</td>";

				    		echo "<td>";
				    		echo anchor("secretary/courseTeachers/{$course['id_course']}", "Visualizar docentes do curso", "class='btn btn-primary'");
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
		<h4>Nenhum curso cadastrado para sua secretaria.</h4>
	</div>
<?php }?>

<?= anchor('secretary_home', 'Voltar', "class='btn btn-danger'	")?>