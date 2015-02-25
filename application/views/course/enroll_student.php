
<br>
<br>
<br>
<br>

<?php

echo "<h2>Matricular alunos no curso <i>".$courseName."</i></h2><br><br>";

displayEnrollStudentForm();

?>

<br>
<br>

<div class='form-group col-xs-6'>
	<?php
	echo form_open('course/enrollStudent');

		echo form_hidden('courseId', $courseId);

	?>

		<div id="search_student_result"></div>
			
	<?php
	echo form_close();
	?>
</div>