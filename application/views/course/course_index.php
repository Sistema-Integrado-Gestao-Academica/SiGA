<h2 align="center">Menu de cursos</h2>

<?=anchor("course/formToRegisterNewCourse", "Cadastrar Curso", array(
	"class" => "btn btn-primary",
	"type" => "submit",
	"content" => "newCourse"
))?>

<?php
	$course = new Course();

	$registered = $course->listAllCourses();
?>

<br>
<br>
<table class="table">
	
	<tr>
		<th>
			Cursos Cadastrados
		</th>
	</tr>
	<tr>
		<th class="text-center">
			Nome do Curso 
		</th>
		<th class="text-center">
			Ações
		</th>
	</tr>
	<?php
	if($registered){
		foreach($registered as $course => $indexes){
			
			echo "<tr>";

				echo "<td>";
				echo $indexes['course_name'];
				echo "</td>";

				echo "<td>";
					
					echo anchor("course/{$indexes['id_course']}", "Editar", array(
					"class" => "btn btn-primary btn-editar",
					"type" => "submit",
					"content" => "Editar"
					));

					echo form_open("course/deleteCourse");
					echo form_hidden("id_course", $indexes['id_course']);
					echo form_button(array(
						"class" => "btn btn-danger btn-remover",
						"type" => "submit",
						"content" => "Remover"
					));
					echo form_close();
				echo "</td>";

			echo "</tr>";
		}
	}else{ ?>
		<tr>
			<td>
				<h3>
					<label class="label label-default"> Não existem cursos cadastrados</label>
				</h3>
			</td>
		</tr>
	<?php }?>
</table>

