<h2 class="principal">Cursos</h2>
<input id="current_course" type="hidden" value="<?=$course['id_course']?>">
<?php
require_once APPPATH.'controllers/usuario.php';
$user = new Usuario();

$hidden = array(
	'id_course' => $course['id_course'],
	'course_name' => $course['course_name']
);

$courseName = array(
	"name" => "courseName",
	"id" => "courseName",
	"type" => "text",
	"class" => "form-campo",
	"class" => "form-control",
	"maxlength" => "50",
	"value" => $course['course_name'],
	"style" => "width: 60%;"
);

$courseDuration = array(
	'2' => '2 anos',
	'4' => '4 anos'
);

$totalCredits = array(
	'name' => 'course_total_credits',
	'id' => 'course_total_credits',
	'maxlength' => '10',
	"class" => "form-control", 
	"value" => $course['total_credits']
);

$courseHours = array(
	'name' => 'course_hours',
	'id' => 'course_hours',
	'maxlength' => '10',
	"class" => "form-control",
	"value" => $course['workload']
);

$courseClass = array(
	'name' => 'course_class',
	'id' => 'course_class',
	'placeholder' => 'Informe o semestre de início.',
	'maxlength' => '6',
	"class" => "form-control",
	"value" => $course['start_class']
);

$description = array(
	'name' => 'course_description',
	'id' => 'course_description',
	'placeholder' => 'Informe a descrição do curso.',
	'rows' => '500',
	"class" => "form-control",
	'style' => 'height: 100px;',
	"value" => $course['description']
);

$submitBtn = array(
	"class" => "btn bg-olive btn-block",
	"type" => "sumbit",
	"content" => "Salvar"
);

$submitButtonFinancial = array(
	"class" => "btn bg-olive btn-block",
	"content" => "Cadastrar Secretário(a) Financeiro(a)",
	"type" => "submit"
);

$submitButtonAcademic = array(
	"class" => "btn bg-olive btn-block",
	"content" => "Cadastrar Secretário(a) Acadêmico(a)",
	"type" => "submit"
);


if($formUserSecretary === FALSE){
	
	$thereIsNoSecretaries = TRUE;
	$formUserSecretary = array("Nenhum secretário cadastrado.");

	$submitButtonAcademic['disabled'] = TRUE;
	$submitButtonFinancial['disabled'] = TRUE;
}else{
	$thereIsNoSecretaries = FALSE;
}

if($registeredPrograms !== FALSE){
	// Nothing to do because there are programs to associate to a course
	$thereAreNoPrograms = FALSE;
}else{
	$thereAreNoPrograms = TRUE;

	$submitBtn['disabled'] = TRUE;
	$registeredPrograms = array("Nenhum programa cadastrado.");
}


?>

<div class="row">
	<div class="col-lg-6">
		<div class="form-box" style="margin-left:-10%;">
			<div class="header">Editar Curso</div>
			<?= form_open("course/updateCourse") ?>
		
			<?php echo form_hidden('id_course', $course['id_course']); ?>
		
				<div class="body bg-gray">
					<div class="form-group">	
						<?= form_label("Nome do Curso", "courseName") ?>
						<?= form_input($courseName) ?>
						<?= form_error("courseName") ?>
					</div>
		
					<div class="form-group">	
						<?= form_label("Tipo de Curso", "courseType") ?>
						<?= form_dropdown("courseType", $form_course_types, $original_course_type, "id='courseType'") ?>
						<?= form_error("courseType") ?>
					</div>

					<div class="form-group">
						<?= form_label("Programa do curso", "courseProgram") ?>
						<?= form_dropdown("courseProgram", $registeredPrograms, $course['id_program'] , "id='courseProgram'") ?>
						<?= form_error("courseProgram") ?>
					</div>
		
					<div class="form-group">
						<?php 
						// Course duration field
						echo form_label('Duração do curso ', 'course_duration');
						echo form_dropdown('course_duration', $courseDuration, $course['duration'], 'id=course_duration');
						?>
					</div>
		
					<div class="form-group">
						<?php
						// Course total credits field
						echo form_label('Créditos totais', 'course_total_credits');
						echo form_input($totalCredits);
						?>
					</div>
		
					<div class="form-group">
						<?php
						// Course hours field
						echo form_label('Carga-horária total', 'course_hours');
						echo form_input($courseHours);
						?>
					</div>
		
					<div class="form-group">
						<?php
						// Course class field
						echo form_label('Turma', 'course_class');
						echo form_input($courseClass);
						?>
					</div>
					
					<div class="form-group">
						<?php
						// Course description field
						echo form_label('Descrição ', 'course_description');
						echo form_textarea($description);
						?>
					</div>
				</div>
				<div class="footer">
					<div class="row">
						<div class="col-xs-6">
							<?= form_button($submitBtn) ?>
						</div>
						<div class="col-xs-6">
							<?= anchor('cursos', 'Voltar', "class='btn bg-olive btn-block'") ?>
						</div>
					</div>
				</div>
			<?= form_close() ?>
			<?php if($thereAreNoPrograms){ ?>
				<div class="callout callout-danger">
					<h4>Não é possível cadastrar um curso sem um programa.</h4>
				</div>
			<?php } ?>
		</div>
	</div>
	<div class="col-lg-6">
		<?php
			  define("FINANCEIRO", 10);
			  define("ACADEMICO", 11);
		?>
		<div class="row">
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
							<?php callout("info", "Não existem secretários cadastrados."); ?>
						</h3>
					</td>
				</tr>
			<?php }?>
		</table>
		</div>

		<div class="row">
			<div class="form-box" id="login-box"> 
				<div class="header">Cadastrar Secretários</div>	
				<div class="body bg-gray">
					
					<div class="form-group">
						<?php 	
						echo form_open("course/saveFinancialSecretary",'',$hidden);
						
						echo form_label("Secretaria Financeira", "financial_secretary") . "<br>";
						echo form_dropdown("financial_secretary", $formUserSecretary, '', "id='financial_secretary'");
						echo form_error("financial_secretary");
						echo "<br>";
						echo "<br>";
						
						echo form_button($submitButtonFinancial);
						echo form_close();
						?>
					</div>
					<div class="form-group">
						<?php 
						echo form_open("course/saveAcademicSecretary",'',$hidden);
						 
						echo form_label("Secretaria Acadêmica", "academic_secretary") . "<br>";
						echo form_dropdown("academic_secretary", $formUserSecretary, '', "id='academic_secretary'");
						echo form_error("academic_secretary");
						echo "<br>";
						echo "<br>";
						
						echo form_button($submitButtonAcademic);
						echo form_close();
						?>
					</div>
					
					<div class="footer body bg-gray">
						<?php if($thereIsNoSecretaries){?>
						<div class="callout callout-danger">
							<h4>Não há secretários cadastrados no sistema.</h4>
						</div>
						<?php } ?>
					</div>
				</div>
				</div>
			</div>
		</div>
	</div>
</div>