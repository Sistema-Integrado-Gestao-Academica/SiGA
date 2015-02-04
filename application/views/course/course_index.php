<h2 align="center">Menu de cursos</h2>

<?= anchor("course/formToRegisterNewCourse", "Cadastrar Curso", array(
	"class" => "btn btn-primary",
	"type" => "submit",
	"content" => "newCourse"
)) ?>

<br><br>

<table class="table">
	<tr>
		<th>Cursos Cadastrados</th>
	</tr>
	<tr>
		<th class="text-center">Nome do Curso</th>
		<th class="text-center">Ações</th>
	</tr>
	<?php if ($courses): ?>
		<?php foreach($courses as $course): ?>
			<tr>
				<td><?= $course['course_name'] ?></td>

				<td>
					<?= anchor("course/{$course['id_course']}", "<span class='glyphicon glyphicon-edit'></span>", "class='btn btn-primary btn-editar btn-sm'") ?>

					<?= form_open('course/deleteCourse') ?>
						<?= form_hidden('id_course', $course['id_course']) ?>
						<button type="submit" class="btn btn-danger btn-remover btn-sm" style="margin: -20px auto auto 100px;">
							<span class="glyphicon glyphicon-remove"></span>
						</button>
					<?= form_close() ?>
				</td>
			</tr>
		<? endforeach ?>
	<? else: ?>
		<tr>
			<td><h3><label class="label label-default"> Não existem cursos cadastrados</label></h3></td>
		</tr>
	<?php endif ?>
</table>
