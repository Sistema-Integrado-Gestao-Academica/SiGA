<h2 class="principal">Funcionários</h2>

<?php 
echo form_open("funcionario/altera");
echo form_hidden("setor_id", $funcionario['id']);

echo form_label("Nome do funcionário", "nome");
echo form_input(array(
	"name" => "nome",
	"id" => "nome",
	"type" => "text",
	"class" => "form-control",
	"maxlength" => "255",
	"value" => set_value("nome", $funcionario['nome'])
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
