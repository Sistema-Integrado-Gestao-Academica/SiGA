<h2 class="principal">Disciplinas</h2>

<?=anchor("course/formToRegisterNewCourse", "Cadastrar Disciplina", array(
	"class" => "btn btn-primary",
	"type" => "submit",
	"content" => "newDiscipline"
))?>
<?php 
	$disciplines = new Discipline();
	$registered = $disciplines->getAllDisciplines();
	
	?>
<br>
<table class="table">
	
	<tr>
		<th>
			Disciplinas Cadastradas
		</th>
	</tr>
	<tr>
		<th class="text-center">
			Nome da Disciplina
		</th>
		<th class="text-center">
			Ações
		</th>
	</tr>
	<?php
	if($registered){
		foreach($registered as $discipline => $indexes){
			
			echo "<tr>";

				echo "<td>";
				echo $indexes['discipline_name'];
				echo "</td>";

				echo "<td>";
					
					echo anchor("discipline/{$indexes['discipline_code']}", "Editar", array(
					"class" => "btn btn-primary btn-editar",
					"type" => "submit",
					"content" => "Editar"
					));

					echo form_open("discipline/deleteDiscipline");
					echo form_hidden("id_course", $indexes['discipline_code']);
					echo form_button(array(
						"class" => "btn btn-danger btn-remover",
						"type" => "submit",
						"content" => "Remover"
					));
					echo form_close();
				echo "</td>";

			echo "</tr>";
		}
	}else{ ?>
		<tr>
			<td>
				<h3>
					<label class="label label-default"> Não existem disciplinas cadastradas</label>
				</h3>
			</td>
		</tr>
	<?php }?>
</table>
	