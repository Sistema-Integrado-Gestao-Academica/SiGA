<h2 class="principal">Disciplinas</h2>

<?=anchor("program/discipline/formToRegisterNewDiscipline", "Cadastrar Disciplina", array(
	"class" => "btn btn-primary",
	"type" => "submit",
	"content" => "newDiscipline"
))?>

<br>
<table class="table table-bordered">

	<tr>
			<h3>Disciplinas Cadastradas</h3>
	</tr>
	<tr>
		<th class="text-center">Código da Disciplina</th>
		<th class="text-center">Nome da Disciplina</th>
		<th class="text-center">Carga Horária Semestral</th>
		<th class="text-center">Restrição</th>
		<th class="text-center">Ações</th>
	</tr>
	<?php

	$tableContent = function($discipline){
	    echo "<tr>";
	        echo "<td>";
	        echo $discipline['discipline_code'];
	        echo "</td>";

	        echo "<td>";
	        echo $discipline['discipline_name'] . " (". $discipline['name_abbreviation'] . ")";
	        echo "</td>";

	        echo "<td>";
	        echo $discipline['workload']." h";
	        echo "</td>";

	        echo "<td>";
	        echo prettyDisciplineRestrict($discipline['restrict']);
	        echo anchor(
	        	"make_discipline_restrict/{$discipline['discipline_code']}",
	        	$discipline['restrict'] ? "Marcar como livre" : "Marcar como restrita",
                "class='btn btn-default'"
            );
	        echo "</td>";

	        echo "<td>";		        echo anchor("discipline/{$discipline['discipline_code']}", "Editar", array(
	                "class" => "btn btn-primary",
	                "content" => "Editar"
		        ));

	        	echo form_open("program/discipline/deleteDiscipline");
		        echo form_hidden("discipline_code", $discipline['discipline_code']);
		        echo form_button(array(
	                "class" => "btn btn-danger",
	                "type" => "submit",
	                "content" => "Remover"
		        ));
		        echo form_close();
	        echo "</td>";
	    echo "</tr>";
	};

	if($disciplines){
		if ($userIsAdmin){
			foreach($disciplines as $courseDisciplines){
				$tableContent($courseDisciplines);
			}
		}else{
			foreach ($disciplines as $courses){
				foreach($courses as $courseDisciplines){
					$tableContent($courseDisciplines);
				}
			}
		}
	}else{ ?>
		<tr>
		<td colspan="4">
			<?= callout("info", "Não existem disciplinas cadastradas.") ?>
		</td>
		</tr>
	<?php }?>
</table>
