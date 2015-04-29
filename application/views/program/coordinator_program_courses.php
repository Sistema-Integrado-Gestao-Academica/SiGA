
<h2 class="principal">Cursos para o programa  <i><?php echo $program['program_name']." - ".$program['acronym']; ?></i> </h2>
<br>

<?php displayProgramCourses($program['id_program'], $programCourses); ?>