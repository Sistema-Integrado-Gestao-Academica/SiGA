<h2 class="principal">Setores</h2>

<?php 
echo form_open("funcao/altera");
echo form_hidden("funcao_id", $funcao['id']);

echo form_label("Nome da função", "nome");
echo form_input(array(
	"name" => "nome",
	"id" => "nome",
	"type" => "text",
	"class" => "form-control",
	"maxlength" => "255",
	"value" => set_value("nome", $funcao['nome'])
));
echo form_error("nome");

echo "<br>";

echo form_button(array(
	"class" => "btn btn-primary",
	"content" => "Alterar",
	"type" => "sumbit"
));

echo form_close();
?>
