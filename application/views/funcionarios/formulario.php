<h2 class="principal">Funcionários</h2>

<table class="table table-striped table-bordered">
	<tr>
		<td><h3 class="text-center">Funcionários cadastrados</h3></td>
	</tr>

<?php 
	if($funcionarios){
		foreach ($funcionarios as $funcionario) { ?>
		<tr>
			<td>
			<?=$funcionario['nome']?>
			</td>
	
			<td>
			<?=anchor("funcionarios/{$funcionario['id']}", "Editar", array(
				"class" => "btn btn-primary btn-editar",
				"type" => "submit",
				"content" => "Editar"
			))?>
	
			<?php 
			echo form_open("funcionario/remove");
			echo form_hidden("funcionario_id", $funcionario['id']);
			echo form_button(array(
				"class" => "btn btn-danger btn-remover",
				"type" => "submit",
				"content" => "Remover"
			));
			echo form_close();
			?>
			</td>
		</tr>
<?php } 
	}else{ ?>
		<tr>
			<td>
				<h3>
					<label class="label label-default"> Não existem funcionários cadastrados</label>
				</h3>
			</td>
		</tr>
	<?php }?>
</table>

<div class="form-box-logged" id="login-box"> 
	<div class="header">Cadastrar um novo funcionário</div>
		<?= form_open("funcionario/novo") ?>
	<div class="body bg-gray">
		<div class="form-group">	
		<?php
		echo form_label("Cadastrar um novo funcionário", "nome");
		echo form_input(array(
			"name" => "nome",
			"id" => "nome",
			"type" => "text",
			"class" => "form-campo",
			"class" => "form-control",
			"maxlength" => "255"
		));
		echo form_error("nome");
		?>
		</div>
	</div>
		<div class="footer">
			<?php 
			echo form_button(array(
				"class" => "btn bg-olive btn-block",
				"type" => "sumbit",
				"content" => "Cadastrar"
			));
			
			echo form_close();
			?>
		</div>
		
</div>