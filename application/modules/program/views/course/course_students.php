<script src=<?=base_url("js/student_list.js")?>></script>

<h2 class="principal">Lista de alunos do curso <i><?php echo $course['course_name']?></i> </h2>

<?php 
	
	require_once(MODULESPATH."secretary/domain/StudentRegistration.php");


?>

		<div class='row'>
			<div class='col-lg-4'>
				<br>	
			    <div class="dropdown">
				  <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
					<i class="fa fa-sort-amount-asc" aria-hidden="true"></i>
					Ordenar por
				    <span class="caret"></span>
				  </button>
				  <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
				    <li><a href="#" onclick='orderByName("<?=$studentsIdsInString?>")'>Nome</button></li>
				    <li><a href="#" onclick='orderByEnrollment("<?=$studentsIdsInString?>")'>Matrícula</a></li>
				    <li><a href="#" onclick='orderByDate("<?=$studentsIdsInString?>")'>Data de matrícula</a></li>
				  </ul>
				</div>
			</div>
			<div class='col-lg-4'>
				<b> Pesquisar pela matrícula </b>
				<?= searchInStudentListByEnrollment($course);?>
			</div>
			<div class='col-lg-4'>
				<b> Pesquisar pelo nome </b>
				<?= searchInStudentListByName($course); ?>
			</div>
		</div>

 
<?php	
	echo "<div id='students_list_table'>";
	displayStudentsTable($students, $course['id_course'], $studentsIdsInString); 



	echo "</div>";
	?>

<?= anchor('secretary/secretary/coursesStudents', 'Voltar', "class='btn btn-danger'")?>
