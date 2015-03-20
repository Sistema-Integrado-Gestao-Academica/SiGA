<br>
<h3 align="left">Solicitações de matrícula do curso <b><i><?php echo $course['course_name'];?></i></b></h3>
<br>

<?php
	displayCourseRequests($requests, $course['id_course']);
?>