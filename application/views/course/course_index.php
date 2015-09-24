<?php
require_once(APPPATH."/constants/GroupConstants.php");

	$group = new Module();
	$isAdmin = $group->checkUserGroup(GroupConstants::ADMIN_GROUP);
?>

<h2 class="principal">Cursos para o secretário(a) <i><b><?php echo $userData['name'];?></b></i></h2>
<br><br>
	
	<?php 
		if($isAdmin){
			echo anchor(
				"course/formToRegisterNewCourse",
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
						<?= anchor("course/formToEditCourse/{$course['id_course']}", "<span class='glyphicon glyphicon-edit'></span>", "class='btn btn-primary' style='margin-right:5%;'") ?>
						
						<?php
							if($isAdmin){
								echo anchor(
									"course/deleteCourse/{$course['id_course']}",
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
	
	<?= anchor("usuario/secretary_research_lines/", "<i class='fa fa-eraser'></i> Gerenciar Linhas de Pesquisa", "class='btn btn-success' style='margin-right:5%;'") ?>
