
<br>
<br>

<h3 align="center">Curso <i><b><?php echo $course['course_name'];?> </b></i></h3>

<h3><span class='label label-primary'> Semestre atual: <?php echo $currentSemester['description'];?> </span></h3>
<br>

<h3 align="center"><b>Oferta</b></h3>
<br>
<?php displayOfferListDisciplines($offerListDisciplines, $course['id_course']); ?>

<?php echo anchor("student/studentCoursePage/{$course['id_course']}/{$userId}", "Voltar", "class='btn btn-danger'"); ?>
