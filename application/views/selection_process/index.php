<h2 class="principal">Processo Seletivo - Programas</h2>

<h4><i class="fa fa-list"></i>	Escolha um programa para ver os cursos disponíveis:</h4>
<?php

buildTableDeclaration();

buildTableHeaders(array(
	'Programas',
	'Ações'
));

if($programs !== FALSE){

	foreach($programs as $program){
		echo "<tr>";

			echo "<td>";
				echo $program['program_name']." - ".$program['acronym'];
			echo "</td>";

			echo "<td>";

				echo anchor(
					"program/selectiveprocess/programCourses/{$program['id_program']}",
					"Cursos do programa",
					"class='btn btn-primary'"
				);

			echo "</td>";

		echo "</tr>";
	}

}else{
	echo "<tr>";
		echo "<td colspan=2>";
			callout("info", "Não existem programas cadastrados para este secretário.");
		echo "</td>";
	echo "</tr>";
}

buildTableEndDeclaration();