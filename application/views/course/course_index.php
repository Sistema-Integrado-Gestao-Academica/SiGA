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

	<?php if ($registered): ?>
		<?php foreach($registered as $course => $indexes): ?>
			<tr>
				<td><?=$indexes['course_name']?></td>

				<td>
					<?= anchor("course/{$indexes['id_course']}", "Editar", array(
						"class" => "btn btn-primary btn-editar",
						"type" => "submit",
						"content" => "Editar"
					)) ?>

					<?= form_open("course/deleteCourse") ?>
						<?= form_hidden("id_course", $indexes['id_course']) ?>
						<?= form_button(array(
							"class" => "btn btn-danger btn-remover",
							"type" => "submit",
							"content" => "Remover"
						)) ?>
					<?= form_close() ?>
				</td>

			</tr>
		<?php endforeach ?>
	<?php else: ?>
		<tr><td><h3><label class="label label-default"> Não existem cursos cadastrados</label></h3></td></tr>
	<?php endif ?>
</table>
