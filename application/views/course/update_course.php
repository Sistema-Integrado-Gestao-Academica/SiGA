<h2 class="principal">Cursos</h2>
<input id="site_url" name="site_url" type="hidden" value="<?php echo $url; ?>" />
<input id="current_course" type="hidden" value="<?php echo $course->id_course; ?>" />

<?php 
require_once APPPATH.'controllers/module.php';
require_once APPPATH.'controllers/usuario.php';

$course_name = $course->course_name;
$course_id = $course->id_course;
$original_course_type = $course->course_type;

$group = new Module();

$form_groups = $group->getExistingModules();

$course_controller = new Course();

$secretary_registered = $course_controller->getCourseSecrecretary($course_id);

$hidden = array('id_course' => $course_id,  
				'original_course_type'=> $original_course_type);

$user = new Usuario();

$form_user_secretary = $user->getAllUsers();


$form_course_type = array(

	'graduation' => 'Graduação',
	'ead' => 'Educação a distância',
	'post_graduation' => 'Pós-Graduação'
);

$course_name_array_to_form = array(
		"name" => "courseName",
		"id" => "courseName",
		"type" => "text",
		"class" => "form-campo",
		"maxlength" => "50",
		"value" => set_value("nome", $course_name),
		"style" => "width: 40%;"
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
<?php
echo form_open("course/updateCourse",'',$hidden);

	// Name field
	echo form_label("Nome do Curso", "courseName");
	echo form_input($course_name_array_to_form);
	echo form_error("courseName");
	echo "<br>";
	echo "<br>";
	
	?>
	
	<h3><span class="label label-primary">Tipo de Curso</span></h3>
	<br>
	
	<?php 
	// User type field
	echo form_label("Tipo de Curso", "courseType");
	echo form_dropdown("courseType", $form_course_type, '', "id='courseType'");
	echo form_error("courseType");
	echo "<br>";

	?>
	<br><div id="post_grad_types"></div>
	
	<div class="row">
		<div class="col-lg-8">
			<div class="row">
				<div id="registered_master_degree"></div>
			</div>
			<div class="row">
				<div id="registered_doctorate"></div>
			</div>
		</div>
		<div class="col-lg-8">
			<br><div id="update_master_degree"></div>
		</div>
	</div>
	<?php

	// Submit button
	echo "<br>";
	echo form_button($submit_button_array_to_form);

echo form_close();
?>
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
					echo form_hidden(array("id_course"=>$course_id, "id_secretary"=>$indexes['id_secretary']));
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
