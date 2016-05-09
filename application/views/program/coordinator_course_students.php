
<h2 class="principal">Alunos do curso <i><?php echo $course['course_name']; ?></i> </h2>
<br>

<?php 

	displayCourseStudents($course['id_course'], $courseStudents);

	echo anchor("coordinator/displayProgramCourses/{$course['id_course']}", "Voltar", "class='btn btn-danger'");
?>

