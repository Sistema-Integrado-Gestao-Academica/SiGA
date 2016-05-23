
<h2 class="principal">Bem vindo, <b><i><?php echo $userData->getName();?></i></b>!</h2>

<?php
	require_once(MODULESPATH."secretary/domain/StudentRegistration.php");

	printCurrentSemester();
			
?>

<br>
<div class="panel panel-primary">

	<div class="panel-heading"><h4>Cursos para o(a) aluno(a) <i><?php echo $userData->getName();?></i></h4></div>

	<div class="panel-body">

		<?php
		if($userCourses !== FALSE){

			foreach ($userCourses as $course) {


				if($course['enrollment'] !== NULL){

					echo anchor("student/studentCoursePage/{$course['id_course']}/{$userData->getId()}", "<b>".$course['course_name']."</b>");
					echo "<br>";
					echo "Data matrícula: ".$course['enroll_date'];
					echo "<br>";
					$registration = new StudentRegistration($course['enrollment']);
					echo "Matrícula: <b>".$registration->getFormattedRegistration()."</b>";
				}else{

					echo "<h4> Curso: <b>{$course['course_name']}</b></h4>";

					$userName = $userData->getName();
					$studentId = $userData->getId();
					$courseId = $course['id_course'];

					include("_inform_enrollment.php");
				}

				echo "<hr>";
			}

		}else{

			callout("info", "Aluno não matriculado em nenhum curso.");
		} ?>

	</div>

	<div class="panel-footer" align="center"><i>Escolha um curso para prosseguir...</i></div>
</div>