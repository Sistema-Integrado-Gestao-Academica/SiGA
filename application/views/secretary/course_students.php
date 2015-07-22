
<h2 class="principal">Lista de alunos do curso <i><?php echo $course['course_name']?></i> </h2>

<?php if($students !== FALSE){ ?>

		<div class="box-body table-responsive no-padding">
		<table class="table table-bordered table-hover">
			<tbody>
				<tr>
			        <th class="text-center">Código</th>
			        <th class="text-center">Aluno</th>
			        <th class="text-center">E-mail</th>
			        <th class="text-center">Data de matrícula</th>
			        <th class="text-center">Status</th>
			        <th class="text-center">Ações</th>
			    </tr>
<?php
			    	foreach($students as $student){

			    		$user = new Usuario();
			    		$studentStatus = $user->getUserStatus($student['id']);

						echo "<tr>";
				    		echo "<td>";
				    		echo $student['id'];
				    		echo "</td>";

				    		echo "<td>";
				    		echo $student['name'];
				    		echo "</td>";

				    		echo "<td>";
				    		echo $student['email'];
				    		echo "</td>";

				    		echo "<td>";
				    		echo $student['enroll_date'];
				    		echo "</td>";

				    		echo "<td>";
				    		echo $studentStatus;
				    		echo "</td>";

				    		echo "<td>";
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
		<h4>Nenhum aluno matriculado neste curso.</h4>
	</div>
<?php }?>

<?= anchor('usuario/secretary_coursesStudents', 'Voltar', "class='btn btn-danger'")?>