

<br>
<br>
<h2 align="center">Bem vindo estudante!</h2>
<br>

<h4>Semestre atual: <?php echo $currentSemester['description'];?></h4>

<p>
    <h3>Cursos:</h3>
    <?php
    if($courses !== FALSE){

    	foreach ($courses as $course) {
    		echo "<b>".$course['course_name']."</b>";
    		echo "<br>";
    		echo "Data matrícula: ".$course['enroll_date'];
    		echo "<br>";
    	}
    }else{
    	echo "Usuário não matriculado em nenhum curso.";
    }
    ?>
    <h3>Status: <?php echo $status;?></h3>
</p>



