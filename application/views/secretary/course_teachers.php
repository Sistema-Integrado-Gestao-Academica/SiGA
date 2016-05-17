
<h2 class="principal">Docentes do curso <b><i><?php echo $course['course_name'];?></i></b></h2>


<?php if($teachers !== FALSE){ ?>
<h4>Docentes cadastrados para o curso:</h4>

		<div class="box-body table-responsive no-padding">
		<table class="table table-bordered table-hover">
			<tbody>
				<tr>
			        <th class="text-center">Código</th>
			        <th class="text-center">Docente</th>
			        <th class="text-center">Situação</th>
			        <th class="text-center">Ações</th>
			    </tr>
<?php
			    	foreach($teachers as $teacher){

						echo "<tr>";
				    		echo "<td>";
				    		echo $teacher['id_user'];
				    		echo "</td>";

				    		echo "<td>";
				    		echo $teacher['name'];
				    		echo "</td>";

				    		echo "<td>";
				    		$teacherSituation = $teacher['situation'];
				    		if($teacherSituation !== NULL){
				    			echo "<b>Situação atual: </b>".$teacherSituation.".<br><br>";
				    			formToDefineTeacherSituation($teacher['id_user'], $teacher['id_course'], $teacher['situation']);
				    		}else{
				    			echo "<b>Situação atual: </b> Não definida.<br><br>";
				    			formToDefineTeacherSituation($teacher['id_user'], $teacher['id_course'], FALSE);
				    		}
				    		echo "</td>";

				    		echo "<td>";
				    		echo anchor(
				    			"secretary/secretary/removeTeacherFromCourse/{$teacher['id_user']}/{$teacher['id_course']}",
				    			"<i class='fa fa-remove'></i> Remover",
				    			"class = 'btn btn-danger'"
				    		);
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
		<h4>Nenhum docente vinculado a este curso.<p> Vincule no formulário abaixo.</h4>
	</div>
<?php }?>

<?php formToEnrollTeacherToCourse($teachers, $allTeachers, $course['id_course']); ?>

<?= anchor('enroll_teacher', 'Voltar', "class='btn btn-danger'")?>