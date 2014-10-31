<h2 class="principal">Departamentos</h2>

<?php 
echo form_open("departamento/altera");
echo form_hidden("departamento_id", $departamento['id']);

echo form_label("Nome do departamento", "nome");
echo form_input(array(
	"name" => "nome",
	"id" => "nome",
	"type" => "text",
	"class" => "form-control",
	"maxlength" => "255",
	"value" => set_value("nome", $departamento['nome'])
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
