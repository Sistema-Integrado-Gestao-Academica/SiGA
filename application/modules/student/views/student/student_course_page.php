
<br>
<br>
<br>

<div class="col-lg-12 col-xs-6">
	<div class="small-box bg-blue">
		<div class="inner">
		    <h2 align="center">Bem vindo ao menu do Curso de <i><b><?php echo $course['course_name'] ?></b></i> !</h2>
		    <p>
		    <div class="list-group">
				<?=anchor("secretary/request/studentEnrollment/{$course['id_course']}/{$user['id']}", "Matrícula", "class='list-group-item' style='width:20%;'");?>
				<?=anchor("student/student/student_offerList/{$course['id_course']}", "Lista de Oferta", "class='list-group-item' style='width:20%;'");?>
				<?=anchor("secretary/syllabus/courseSyllabus/{$course['id_course']}", "Currículo do curso", "class='list-group-item' style='width:20%;'");?>
			</div>
        	</p>

		</div>
		<div class="icon">
		    <i class="fa fa-pencil"></i>
		</div>
	</div>
</div>

<?php echo anchor("student", "Voltar", "class='btn btn-danger'"); ?>
