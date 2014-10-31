<h2 class="principal">Setores</h2>

<table class="table table-striped table-bordered">
	<tr>
		<td><h3 class="text-center">Setores cadastrados</h3></td>
		<td></td>
	</tr>

<?php foreach ($setores as $setor) { ?>
	<tr>
		<td>
		<?=$setor['nome']?>
		</td>

		<td>
		<?=anchor("setores/{$setor['id']}", "Editar", array(
			"class" => "btn btn-primary btn-editar",
			"type" => "sumbit",
			"content" => "Editar"
		))?>

		<?php 
		echo form_open("setor/remove");
		echo form_hidden("setor_id", $setor['id']);
		echo form_button(array(
			"class" => "btn btn-danger btn-remover",
			"type" => "sumbit",
			"content" => "Remover"
		));
		echo form_close();
		?>
		</td>
	</tr>
<?php } ?>
</table>

<br><br>

<?php 
echo form_open("setor/novo");

echo form_label("Cadastrar um novo setor", "nome");
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