<h2 class="principal"></i>Ações para <b><?= $student['name'] ?></b> </h2>

<div class="row">

<div class="col-lg-6">
	<?php
		include "change_student_enrollment.php";
	?>
</div>
	<?php
		include "change_enroll_semester.php";
	?>

</div>

<?= anchor('program/course/courseStudents/'.$courseId, 'Voltar', "class='btn btn-danger'")?>