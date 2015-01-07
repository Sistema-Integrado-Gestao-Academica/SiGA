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

<br><br>

<?php 
echo form_open("funcionario/novo");

echo form_label("Cadastrar um novo funcionário", "nome");
echo form_input(array(
	"name" => "nome",
	"id" => "nome",
	"type" => "text",
	"class" => "form-campo",
	"maxlength" => "255"
));
echo form_error("nome");

echo "<br>";

echo form_button(array(
	"class" => "btn btn-primary",
	"type" => "sumbit",
	"content" => "Cadastrar"
));

echo form_close();
?>