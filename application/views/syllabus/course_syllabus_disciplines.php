
<br>
<h3 align="left">Currículo do curso <b><?php echo $course['course_name']?></b></h3>
<h4>Código do currículo: <b><?php echo $syllabusId; ?></b></h4>
<br>

<?php 

	displaySyllabusDisciplines($syllabusId, $syllabusDisciplines, $course['id_course']); 

	echo anchor('usuario/secretary_courseSyllabus',"Voltar", "class='btn btn-primary'");

?>