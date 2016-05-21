
<br>
<br>
<br>
<h2 align="center">Bem vindo estudante!</h2>
<br>

<h3><span class='label label-primary'> Semestre atual: <?php echo $currentSemester	['description'];?> </span></h3>
<br>

<div class="panel panel-primary">

	<div class="panel-heading"><h4>Cursos para o(a) aluno(a) <i><?php echo $userData->getName();?></i></h4></div>

	<div class="panel-body">

		<?php
		if($courses !== FALSE){

			foreach ($courses as $course) {
				$id = $userData->getId();
				echo anchor("student/studentCoursePage/{$course['id_course']}/{$id}", "<b>".$course['course_name']."</b>");
				echo "<br>";
				echo "Data matrícula: ".$course['enroll_date'];
				echo "<hr>";
			}

		}else{
		?>

		<div class="callout callout-info">
			<h4>Aluno não matriculado em nenhum curso.</h4>
		</div>

		<?php } ?>
		<!-- <h3>Status: <?php echo $status;?></h3> -->

	</div>

	<div class="panel-footer" align="center"><i>Escolha um curso para prosseguir...</i></div>
</div>
