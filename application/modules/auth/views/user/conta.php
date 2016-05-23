<h2 class="text-center">Conta</h2>

<?php
echo form_open("update_user_profile");

echo form_hidden('id', $user->getId());
echo form_hidden('oldEmail', $user->getEmail());

$value = $user->getName();
echo form_label("Nome", "name");
echo form_input(array(
	"name" => "name",
	"id" => "name",
	"type" => "text",
	"class" => "form-campo",
	"maxlength" => "255",
	"value" => $value
));
echo form_error("name");

$value = $user->getEmail();
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

$value = $user->getLogin();
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

$value = $user->getHomePhone();
echo form_label("Telefone Residencial", "phone");
echo form_input(array(
	"name" => "home_phone",
	"id" => "home_phone",
	"type" => "text",
	"class" => "form-campo",
	"maxlength" => "11",
	"value" => $value
));
echo form_error("phone");

$value = $user->getCellPhone();
echo form_label("Telefone Celular", "phone");
echo form_input(array(
	"name" => "cell_phone",
	"id" => "cell_phone",
	"type" => "text",
	"class" => "form-campo",
	"maxlength" => "11",
	"value" => $value
));
echo form_error("phone");

/* ------------------------------------------------------------------------ */

?><br><br><h4>Alterar Senha</h4><br><?php

echo form_label("Senha atual", "password");
echo form_password(array(
	"name" => "password",
	"id" => "password",
	"class" => "form-campo",
	"maxlength" => "255"
));
echo form_error("password");

echo form_label("Nova senha", "new_password");
echo form_password(array(
	"name" => "new_password",
	"id" => "new_password",
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

echo form_open("userController/remove", "onsubmit='return deleteAccount()'");

echo form_button(array(
	"id" => "delete",
	"class" => "btn btn-danger",
	"content" => "Apagar conta",
	"type" => "submit"
));

echo form_close();
?>