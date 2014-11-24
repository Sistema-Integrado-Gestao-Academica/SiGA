<h2 class="text-center">Conta</h2>

<?php 
echo form_open("usuario/altera");

$value = $usuario['name'];
echo form_label("Nome", "nome");
echo form_input(array(
	"name" => "nome",
	"id" => "nome",
	"type" => "text",
	"class" => "form-campo",
	"maxlength" => "255",
	"value" => $value
));
echo form_error("nome");

$value = $usuario['email'];
echo form_label("E-mail", "email");
echo form_input(array(
	"name" => "email",
	"id" => "email",
	"type" => "text",
	"class" => "form-campo",
	"maxlength" => "255",
	"value" => $value
));
echo form_error("email");

$value = $usuario['login'];
echo form_label("Login", "login");
echo form_input(array(
	"disabled" => "disabled",
	"name" => "login",
	"id" => "login",
	"type" => "text",
	"class" => "form-campo",
	"maxlength" => "255",
	"value" => $value
));
echo form_error("login");

/* ------------------------------------------------------------------------ */

?><br><br><h4>Alterar Senha</h4><br><?php

echo form_label("Senha atual", "senha");
echo form_password(array(
	"name" => "senha",
	"id" => "senha",
	"class" => "form-campo",
	"maxlength" => "255"
));
echo form_error("password");

echo form_label("Nova senha", "nova_senha");
echo form_password(array(
	"name" => "nova_senha",
	"id" => "nova_senha",
	"class" => "form-campo",
	"maxlength" => "255"
));
echo form_error("password");

echo "<br>";

echo form_button(array(
	"class" => "btn btn-primary",
	"content" => "Confirmar",
	"type" => "submit"
));

echo form_close();

/* ------------------------------------------------------------------------ */

echo form_open("usuario/remove", "onsubmit='return apagar_conta()'");

echo form_button(array(
	"id" => "delete",
	"class" => "btn btn-danger",
	"content" => "Apagar conta",
	"type" => "submit"
));

echo form_close();
?>