<?php 
	$session = $this->session->userdata("current_user");
	require_once APPPATH.'controllers/course.php';
?>

<?php
$userIsAcademicSecretary = array_key_exists('courseSecretaryAcademic', $session['user_permissions']);

if ($userIsAcademicSecretary):
	$courseController = new Course();
	
	$coursesForSecretary = $courseController->getCoursesOfSecretary($session['user']['id']);
?>
<h2 class="principal">Meus cursos</h2>
<br><br>
	
	<table class="table table-striped table-bordered">
		<tr>
			<td><h3 class="text-center">Cursos Cadastrados</h3></td>
			<td><h3 class="text-center">Ações</h3></td>
		</tr>
			<?php foreach($coursesForSecretary as $course): ?>
				<tr>
					<td class="text-center"><?= $course['course_name'] ?></td>
	
					<td>
						<?= anchor("course/formToEditCourse/{$course['id_course']}", "<span class='glyphicon glyphicon-edit'></span>", "class='btn btn-primary' style='margin-right:5%;'") ?>
					</td>
				</tr>
			<?php endforeach ?>
	</table>
	
	<br>
	<br>
<?php 
else :
?>
<h2 class="principal">Menu de cursos</h2>
	<?= anchor("course/formToRegisterNewCourse", "Cadastrar Curso", array(
		"class" => "btn btn-primary",
		"type" => "submit",
		"content" => "newCourse"
	)) ?>
	
	<br><br>
	
	<?php displayRegisteredCourses($courses); ?>
	
	<br>
	<br>
<?php endif;?>


<?= anchor("program/registerNewProgram", "Cadastrar Programa", "class='btn btn-primary'") ?>

<br><br>

<?php displayRegisteredPrograms($programs); ?>
