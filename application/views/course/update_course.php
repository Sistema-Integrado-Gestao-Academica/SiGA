<h2 class="principal">Cursos</h2>
<input id="site_url" name="site_url" type="hidden" value="<?=$url?>">
<input id="current_course" type="hidden" value="<?=$course['id_course']?>">
<?php
require_once APPPATH.'controllers/usuario.php';
$user = new Usuario();

$hidden = array(
	'id_course' => $course['id_course'], 
	'original_course_type' => $course['course_type']
);

$course_name_array_to_form = array(
		"name" => "courseName",
		"id" => "courseName",
		"type" => "text",
		"class" => "form-campo",
		"maxlength" => "50",
		"value" => set_value("nome", $course['course_name']),
		"style" => "width: 60%;"
);

$submit_button_array_to_form = array(
		"class" => "btn btn-primary",
		"content" => "Alterar",
		"type" => "submit"
);

$submit_button_array_to_form_secretary = array(
		"class" => "btn btn-primary",
		"content" => "Cadastrar",
		"type" => "submit"
);
?>

<div class="row">
	<div class="col-lg-6">
		<?= form_open("course/updateCourse", '', $hidden) ?>
			
			<?= form_label("Nome do Curso", "courseName") ?>
			<?= form_input($course_name_array_to_form) ?>
			<?= form_error("courseName") ?>


			<h3><span class="label label-primary">Tipo de Curso</span></h3>
			<br>
		<?= form_label("Tipo de Curso", "courseType") ?>
			<?= form_dropdown("courseType", $form_course_type, $course['course_type_id'], "id='courseType'") ?>
			<?= form_error("courseType") ?>

			<br><br><br>

			<?= form_button($submit_button_array_to_form) ?>
		<?= form_close() ?>
	</div>
	
<div class="col-lg-6">
<?php
	  define("FINANCEIRO", 1);
	  define("ACADEMICO", 2);
?>
<table class="table">

	<h4><span class="label label-primary">Secretarios Cadastrados</span></h4>
	<tr>
		<th>
			Nome do Secretario
		</th>
		<th>
			Tipo de Secretario
		</th>
		
	</tr>
	<?php
	if($secretary_registered){
		foreach($secretary_registered as $secretary => $indexes){
			
			echo "<tr>";

				echo "<td>";
				print_r($user->getUserNameById($indexes['id_user']));
				echo "</td>";
				
				echo "<td>";
				if($indexes['id_group'] == FINANCEIRO){
					echo "Financeiro";
				}else if($indexes['id_group'] == ACADEMICO){
					echo "Acadêmico";
				}
				echo "</td>";
				
				echo "<td>";
					echo "<br>";
					echo form_open("course/deleteSecretary");
					echo form_hidden(array("id_course"=>$course['id_course'], "id_secretary"=>$indexes['id_secretary']));
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
					<label class="label label-default"> Não existem cursos cadastrados</label>
				</h3>
			</td>
		</tr>
	<?php }?>
</table>




<h3><span class="label label-primary">Secretaria</span></h3>
	<br>
	
	<div class="form-box" id="login-box"> 
		<div class="header">Cadastrar Secretários</div>	
		<?php 
		echo form_open("course/saveSecretary",'',$hidden);
		?>
		<div class="body bg-gray">
			<div class="form-group">
				<?php 	
				//secretary field
				echo form_label("Secretaria Financeira", "financial_secretary") . "<br>";
				echo form_dropdown("financial_secretary", $form_user_secretary, '', "id='financial_secretary'");
				echo form_error("financial_secretary");
				echo "<br>";
				echo "<br>";
				?>
			</div>
			<div class="form-group">
				<?php 
				echo form_label("Secretaria Acadêmica", "academic_secretary") . "<br>";
				echo form_dropdown("academic_secretary", $form_user_secretary, '', "id='academic_secretary'");
				echo form_error("academic_secretary");
				echo "<br>";
				echo "<br>";
				?>
			</div>
					
		</div>
		<div class="footer">
			<?php 
			// Submit button
			echo form_button($submit_button_array_to_form_secretary);
			echo form_close();
			?>
		</div>
	</div>
</div>
</div>
