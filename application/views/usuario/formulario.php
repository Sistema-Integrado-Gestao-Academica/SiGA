<h2 class="text-center">Cadastro de um novo usuário</h2>

<?php 
echo form_open("usuario/novo");

echo form_label("Nome", "nome");
echo form_input(array(
	"name" => "nome",
	"id" => "nome",
	"type" => "text",
	"class" => "form-campo",
	"maxlength" => "255",
	"value" => set_value("nome", "")
));
echo form_error("nome");

echo form_label("E-mail", "email");
echo form_input(array(
	"name" => "email",
	"id" => "email",
	"type" => "text",
	"class" => "form-campo",
	"maxlength" => "255",
	"value" => set_value("email", "")
));
echo form_error("email");

echo form_label("Login", "login");
echo form_input(array(
	"name" => "login",
	"id" => "login",
	"type" => "text",
	"class" => "form-campo",
	"maxlength" => "255",
	"value" => set_value("login", "")
));
echo form_error("login");

echo form_label("Senha", "senha");
echo form_password(array(
	"name" => "senha",
	"id" => "senha",
	"class" => "form-campo",
	"maxlength" => "255"
));
echo form_error("password");

echo "<br>";

echo form_button(array(
	"class" => "btn btn-primary",
	"content" => "Cadastrar",
	"type" => "submit"
));

echo form_close();
?>
