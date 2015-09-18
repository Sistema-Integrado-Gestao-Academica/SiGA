<h2 class="principal">Disciplinas</h2>

<?=anchor("discipline/formToRegisterNewDiscipline", "Cadastrar Disciplina", array(
	"class" => "btn btn-primary",
	"type" => "submit",
	"content" => "newDiscipline"
))?>
<?php
	define("ADMINID", 1);
	$session = $this->session->userdata("current_user");
	$userId = $session['user']['id']; 
	
	$disciplines = new Discipline();
	if ($userId == ADMINID){
		$registered = $disciplines->getAllDisciplines();
	}else{

		$registered = $disciplines->getDisciplinesBySecretary($userId);
	}
	?>
<br>
<table class="table table-bordered">
	
	<tr>
			<h3>Disciplinas Cadastradas</h3>
	</tr>
	<tr>
		<th class="text-center">
			Nome da Disciplina
		</th>
		<th class="text-center">
			Código da Disciplina
		</th>
		<th class="text-center">
			Carga Horária Semestral
		</th>
		<th class="text-center">
			Ações
		</th>
	</tr>
	<?php
	if($registered){
		if ($userId == ADMINID){
			foreach($registered as $discipline => $indexes){
				
				echo "<tr>";
	
					echo "<td>";
					echo $indexes['discipline_name'] . " (". $indexes['name_abbreviation'] . ")";
					echo "</td>";
					
					echo "<td>";
					echo $indexes['discipline_code'];
					echo "</td>";
					
					echo "<td>";
					echo $indexes['workload']." h";
					echo "</td>";
					
					echo "<td>";
						
						echo anchor("discipline/{$indexes['discipline_code']}", "Editar", array(
						"class" => "btn btn-primary btn-editar",
						"type" => "submit",
						"content" => "Editar"
						));
	
						echo form_open("discipline/deleteDiscipline");
						echo form_hidden("discipline_code", $indexes['discipline_code']);
						echo form_button(array(
							"class" => "btn btn-danger btn-remover",
							"type" => "submit",
							"content" => "Remover"
						));
						echo form_close();
					echo "</td>";
	
				echo "</tr>";
			}
		}else{
			foreach ($registered as $courses){
				foreach($courses as $discipline => $indexes){
	
					echo "<tr>";
				
					echo "<td>";
					echo $indexes['discipline_name'] . " (". $indexes['name_abbreviation'] . ")";
					echo "</td>";
						
					echo "<td>";
					echo $indexes['discipline_code'];
					echo "</td>";
						
					echo "<td>";
					echo $indexes['workload']." h";
					echo "</td>";
						
					echo "<td>";
				
					echo anchor("discipline/{$indexes['discipline_code']}", "Editar", array(
							"class" => "btn btn-primary btn-editar",
							"type" => "submit",
							"content" => "Editar"
					));
				
					echo form_open("discipline/deleteDiscipline");
					echo form_hidden("discipline_code", $indexes['discipline_code']);
					echo form_button(array(
							"class" => "btn btn-danger btn-remover",
							"type" => "submit",
							"content" => "Remover"
					));
					echo form_close();
					echo "</td>";
				
					echo "</tr>";
				}
			}
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
	