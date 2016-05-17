<br>
<h4 align="left"><b>Relação atual de Orientadores e Estudantes</b></h4>
<br>

<?php
	
echo anchor("enrollMastermind/{$courseId}","<i class='fa fa-plus-circle'></i> Cadastrar Orientador", "class='btn-lg'");
	echo "<br>";
	echo "<br>";
	buildTableDeclaration();
	if ($relationsToTable !== FALSE){
		buildTableHeaders(array(
			'Orientador',
			'Estudante',
			'Ações'
		));
		foreach ($relationsToTable as $mastermindAndStudent){
			echo "<tr>";
				echo "<td>";
				echo $mastermindAndStudent['mastermind_name'];
				echo "</td>";
				echo "<td>";
				echo $mastermindAndStudent['student_name'];
				echo "</td>";
				echo "<td>";
				echo anchor("program/mastermind/deleteMastermindStudentRelation/{$mastermindAndStudent['mastermind_id']}/{$mastermindAndStudent['student_id']}/{$courseId}","<i class='glyphicon glyphicon-remove'></i>", "class='btn btn-danger'");
				echo anchor("program/mastermind/titlingAreaUpdateBySecretary/{$mastermindAndStudent['mastermind_id']}","<i class='fa fa-pencil'>Editar area de titulação</i>", "class='btn btn-default'");
				echo "</td>";
			echo "</tr>";
		}
	}else{
		$message = "Não existem orientadores designados no momento.";
		callout("info", $message);
	}
	buildTableEndDeclaration();
