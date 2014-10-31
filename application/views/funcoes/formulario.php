<h2 class="principal">Funções</h2>

<table class="table table-striped table-bordered">
	<tr>
		<td><h3 class="text-center">Funções cadastradas</h3></td>
		<td></td>
	</tr>

<?php foreach ($funcoes as $funcao) { ?>
	<tr>
		<td>
		<?=$funcao['nome']?>
		</td>

		<td>
		<?=anchor("funcoes/{$funcao['id']}", "Editar", array(
			"class" => "btn btn-primary btn-editar",
			"type" => "sumbit",
			"content" => "Editar"
		))?>
		
		<?php 
		echo form_open("funcao/remove");
		echo form_hidden("funcao_id", $funcao['id']);
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
echo form_open("funcao/novo");

echo form_label("Cadastrar uma nova função", "nome");
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