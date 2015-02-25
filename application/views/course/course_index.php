<h2 class="principal">Menu de cursos</h2>

<?= anchor("course/formToRegisterNewCourse", "Cadastrar Curso", array(
	"class" => "btn btn-primary",
	"type" => "submit",
	"content" => "newCourse"
)) ?>

<br><br>

<table class="table table-striped table-bordered">
	<tr>
		<td><h3 class="text-center">Cursos Cadastrados</h3></td>
		<td><h3 class="text-center">Ações</h3></td>
	</tr>
	<?php if ($courses !== FALSE): ?>
		<?php foreach($courses as $course): ?>
			<tr>
				<td class="text-center"><?= $course['course_name'] ?></td>

				<td>
					<?= anchor("course/formToEditCourse/{$course['id_course']}", "<span class='glyphicon glyphicon-edit'></span>", "class='btn btn-primary' style='margin-right:5%;'") ?>
					<?= anchor("course/deleteCourse/{$course['id_course']}", "<span class='glyphicon glyphicon-remove'></span>", "class='btn btn-danger'") ?>
				</td>
			</tr>
		<?php endforeach ?>
	<?php else: ?>
		<tr>
			<td><h3><label class="label label-default"> Não existem cursos cadastrados</label></h3></td>
		</tr>
	<?php endif ?>
</table>

<br>
<br>

<?= anchor("program/registerNewProgram", "Cadastrar Programa", "class='btn btn-primary'") ?>

<br><br>

<?php displayRegisteredPrograms($programs); ?>
