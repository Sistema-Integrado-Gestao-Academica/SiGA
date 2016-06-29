<?php
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

?>
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
						echo($indexes['user_name']);
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
							echo form_open("program/course/deleteSecretary");
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
						echo form_open("program/course/saveFinancialSecretary",'',$hidden);
						
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
						echo form_open("program/course/saveAcademicSecretary",'',$hidden);
						 
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