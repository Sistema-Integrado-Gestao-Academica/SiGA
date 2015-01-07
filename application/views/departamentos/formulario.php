<h2 class="principal">Departamentos</h2>

<table class="table table-striped table-bordered">
	<tr>
		<td><h3 class="text-center">Departamentos cadastrados</h3></td>
	</tr>

<?php 
	if($departamentos){
		foreach ($departamentos as $departamento) { ?>
			<tr>
				<td>
				<?=$departamento['nome']?>
				</td>
		
				<td>
				<?=anchor("departamentos/{$departamento['id']}", "Editar", array(
					"class" => "btn btn-primary btn-editar",
					"type" => "sumbit",
					"content" => "Editar"
				))?>
				
				<?php 
				echo form_open("departamento/remove");
				echo form_hidden("departamento_id", $departamento['id']);
				echo form_button(array(
					"class" => "btn btn-danger btn-remover",
					"type" => "sumbit",
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
					<label class="label label-default"> Não existem departamentos cadastrados</label>
				</h3>
			</td>
		</tr>
	<?php }?>
</table>

<br><br>

<?php 
echo form_open("departamento/novo");

echo form_label("Cadastrar um novo departamento", "nome");
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