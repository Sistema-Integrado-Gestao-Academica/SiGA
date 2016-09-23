<script src=<?=base_url("js/student_list.js")?>></script>

<h2 class="principal">Lista de alunos do curso <i><?php echo $course['course_name']?></i> </h2>

<?php 
	
	require_once(MODULESPATH."secretary/domain/StudentRegistration.php");

	echo "<div class='row'>";
		echo "<div class='col-lg-6'>";
			searchInStudentListByEnrollment($course);
		echo "</div>";
		echo "<div class='col-lg-6'>";
			searchInStudentListByName($course);
		echo "</div>";
	echo "</div>";

	echo "<div id='students_list_table'>";

	echo "<br><br>";
	
	displayStudentsTable($students, $course['id_course']);

?>
<?= anchor('secretary/secretary/coursesStudents', 'Voltar', "class='btn btn-danger'")?>