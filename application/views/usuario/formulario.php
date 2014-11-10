<h2 class="text-center">Cadastro de um novo usuário</h2>

<?php 

$user = new Usuario();

$user_types = $user->getUserTypes();

for($cont = 0 ; $cont<sizeof($user_types); $cont++){
	
	$keys[$cont] = $user_types[$cont]['id_type'];
	$values[$cont] = $user_types[$cont]['type_name'];
	
}

$form_user_types = array_combine($keys, $values);

echo form_open("usuario/novo");

echo form_label("Nome", "nome");
echo form_input(array(
	"name" => "nome",
	"id" => "nome",
	"type" => "text",
	"class" => "form-campo",
	"maxlength" => "70",
	"value" => set_value("nome", "")
));
echo form_error("nome");

echo form_label("E-mail", "email");
echo form_input(array(
	"name" => "email",
	"id" => "email",
	"type" => "text",
	"class" => "form-campo",
	"maxlength" => "50",
	"value" => set_value("email", "")
));
echo form_error("email");

echo form_label("Tipo de Usuário", "userType");

echo "<br>";
echo form_dropdown("userType",$form_user_types);

echo form_error("userType");

echo "<br>";

echo form_label("Login", "login");
echo form_input(array(
	"name" => "login",
	"id" => "login",
	"type" => "text",
	"class" => "form-campo",
	"maxlength" => "20",
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
echo form_error("senha");

echo "<br>";

echo form_button(array(
	"class" => "btn btn-primary",
	"content" => "Cadastrar",
	"type" => "submit"
));

echo form_close();
?>
