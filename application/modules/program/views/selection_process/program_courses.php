<h2 class="principal">Processo Seletivo - Cursos</h2>

<h4><i class="fa fa-list"></i>	Escolha um curso para abrir um edital:</h4>
<?php

buildTableDeclaration();

buildTableHeaders(array(
	'Cursos',
	'Ações'
));

if($courses  !== FALSE){

	foreach($courses  as $course ){
		echo "<tr>";

			echo "<td>";
				echo $course['course_name'];
			echo "</td>";

			echo "<td>";

				echo anchor(
					"program/selectiveprocess/courseSelectiveProcesses/{$course['id_course']}",
					"<i class='fa fa-list'></i> Editais do curso <b>".$course['course_name']."</b>",
					"class='btn btn-primary'"
				);

			echo "</td>";

		echo "</tr>";
	}

}else{
	echo "<td colspan=2>";
		callout("info", "Não existem cursos nesse programa cadastrados para este secretário.");
	echo "</td>";
}

buildTableEndDeclaration();

echo  "<br>";

echo anchor("selection_process","Voltar","class='btn btn-danger'");