<h2 class='principal'>Matricular alunos no curso <i><?=$course['course_name']?></i></h2>
<?php
/*
 * Legacy code. Waiting for reuse on search for guests users to enrollment
 */
//displayEnrollStudentForm();

?>

<br>
<br>

<?php

if($guests !== FALSE){
	echo "<h3>Lista de Usuários que podem ser Matriculados</h3>";

	buildTableDeclaration();

	buildTableHeaders(array(
		'Nome',
		'E-mail',
		'Ações'
	));

	foreach ($guests as $user){
		echo "<tr>";
			echo "<td>";
				echo $user['name'];
			echo "</td>";
			echo "<td>";
				echo $user['email'];
			echo "</td>";
			echo "<td>";
				echo anchor("enrollment/enrollStudent/{$course['id_course']}/{$user['id']}", "Matricular", "class='btn btn-primary'");
			echo "</td>";

		echo "</tr>";
	}

	buildTableEndDeclaration();
}else{
	callout("info", "Não existem usuários disponíveis para matrícula no momento.");
}


?>
