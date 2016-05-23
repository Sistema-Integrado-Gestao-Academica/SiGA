
<h2 class="principal">Cursos para o secretário(a) <i><b><?php echo $userData->getName();?></b></i></h2>
<br><br>

	<?php
		if($isAdmin){
			echo anchor(
				"program/course/formToRegisterNewCourse",
				"<i class='fa fa-plus-circle'></i> Cadastrar Curso",
				"class='btn-lg'"
			);
			echo "<br><br>";
		}

		if($courses !== FALSE){
	?>
		<div class="box-body table-responsive no-padding">
			<table class="table table-bordered table-hover">
			<tbody>
				<tr>
					<th class="text-center">Cursos Cadastrados</th>
					<th class="text-center">Ações</th>
				</tr>

		<?php foreach($courses as $course): ?>
				<tr>
					<td class="text-center">
						<?= $course['course_name'] ?>
					</td>

					<td>
						<?= anchor("program/course/formToEditCourse/{$course['id_course']}", "<span class='glyphicon glyphicon-edit'></span>", "class='btn btn-primary' style='margin-right:5%;'") ?>

						<?php
							if($isAdmin){
								echo anchor(
									"program/course/deleteCourse/{$course['id_course']}",
									"<span class='glyphicon glyphicon-remove'></span>",
									"class='btn btn-danger'"
								);
							}
						?>
					</td>
				</tr>
		<?php endforeach ?>

			</tbody>
			</table>
			</div>

		<?php }else{ ?>

			<div class="callout callout-info">
				<h4>Nenhum curso cadastrado.</h4>
			</div>

		<?php } ?>

	<?= anchor("program/course/research_lines/", "<i class='fa fa-eraser'></i> Gerenciar Linhas de Pesquisa", "class='btn btn-success' style='margin-right:5%;'") ?>
