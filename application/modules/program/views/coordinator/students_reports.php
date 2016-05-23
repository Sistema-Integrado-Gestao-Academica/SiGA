<br>
<br>

<?php 

	echo "<h4> Painel de quantidades de alunos </h4>";

	buildTableDeclaration();

	buildTableHeaders(array(
		'Total de estudantes',
		'Total de matriculados',
		'Total de atrasados'
	));

	echo "<tr>";
		echo "<td>";
		echo $totalStudent;
		echo "</td>";
		echo "<td>";
		echo $enroledStudents;
		echo "</td>";
		echo "<td>";
		echo $notEnroledStudents;
		echo "</td>";
	echo "</tr>";

	buildTableEndDeclaration();

?>