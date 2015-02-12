<input id="site_url" name="site_url" type="hidden" value="<?php echo $url; ?>"></input>

<?php
require_once APPPATH.'controllers/module.php';
require_once APPPATH.'controllers/usuario.php';

$group = new Module();

$form_groups = $group->getExistingModules();

$user = new Usuario();

$form_user_secretary = $user->getAllSecretaryUsers();

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
		"class" => "form-control",
		"maxlength" => "50",
		"value" => set_value("nome", ""),
);

$submit_button_array_to_form = array(
		"class" => "btn bg-olive btn-block",
		"type" => "sumbit",
		"content" => "Cadastrar"
);
?>

<div class="form-box" id="login-box">
<div class="header">Cadastrar um novo Curso</div>
		<?= form_open("course/newCourse") ?>
	<div class="body bg-gray">
		<div class="form-group">	
		<?php
			// Name field
			echo form_label("Nome do Curso", "courseName");
			echo form_input($course_name_array_to_form);
			echo form_error("courseName");
		?>
		</div>
		<div class="form-group">	
		<?php
			// User type field
			echo form_label("Tipo de Curso", "courseTypeLabel");
			echo form_dropdown("courseType", $form_course_type, '', "id='courseType'");
			echo form_error("courseType");
		?>
		</div>
		
<!-- 	DEPRECATED CODE	
		<div class="form-group">	 -->
		<?php
// 			// Secretary field
// 			echo form_label("Tipo de Secretaria", "secreteary_type");
// 			echo form_dropdown("secretary_type", $form_groups);
// 			echo form_error("secretary_type");
// 		?>
<!-- 		</div> -->
<!-- 		<div class="form-group">	 -->
		<?php
// 			echo form_label("Escolher secretário", "user_secretary");
// 			echo form_dropdown("user_secretary", $form_user_secretary);
// 			echo form_error("user_secretary");
// 		?>
<!-- 		</div>  
		END OF DEPRECATED CODE-->
		
		<div class="form-group">	
			<div id="post_grad_types"></div>
		</div>
		<div class="form-group">	
			<div id="chosen_post_grad_type"></div>
		</div>
	</div>
		<div class="footer">
			<?php 
			echo form_button($submit_button_array_to_form);
			
			echo form_close();
			?>
		</div>
		
</div>