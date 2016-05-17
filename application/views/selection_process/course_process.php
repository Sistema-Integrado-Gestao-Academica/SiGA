
<h2 class="principal">Processos Seletivos do curso <b><i><?=$course[Course_model::COURSE_NAME_ATTR]?></i></b> </h2>

<?php

echo anchor(
	"program/selectiveprocess/openSelectiveProcess/{$course[Course_model::ID_ATTR]}",
	"<i class='fa fa-plus-square'></i> Abrir edital para <b>".$course[Course_model::COURSE_NAME_ATTR]."</b>",
	"class = 'btn btn-lg'"
);

buildTableDeclaration();

buildTableHeaders(array(
	'Edital',
	'Tipo',
	'Ações'
));

if($selectiveProcesses !== FALSE){

	foreach($selectiveProcesses as $process){
		echo "<tr>";

			echo "<td>";
				echo "<h4><span class='label label-primary'>".$process->getName()."</span></h4>";
			echo "</td>";

			echo "<td>";
				echo $process->getFormmatedType();
			echo "</td>";

			echo "<td>";

			echo "</td>";

		echo "</tr>";
	}

}else{
	echo "<tr>";
		echo "<td colspan=3>";
			callout("info", "Não existem processos seletivos abertos para este curso.");
		echo "</td>";
	echo "</tr>";
}

buildTableEndDeclaration();

echo "<br>";

echo anchor("program/selectiveprocess/programCourses/{$course['id_program']}", "Voltar", "class='btn btn-danger'");