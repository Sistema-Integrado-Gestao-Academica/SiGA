<h2 class="text-center">Cadastro de um novo usuário</h2>

<?php 

$form_user_types = array(2=>"aluno",5=>"convidado");

$name_array_to_form = array(
	"name" => "nome",
	"id" => "nome",
	"type" => "text",
	"class" => "form-campo",
	"maxlength" => "70",
	"value" => set_value("nome", "")
);

$cpf_array_to_form = array(
		"name" => "cpf",
		"id" => "cpf",
		"type" => "text",
		"class" => "form-campo",
		"maxlength" => "11",
		"value" => set_value("cpf", ""),
		"placeholder" => "Somente Números"
);

$email_array_to_form = array(
	"name" => "email",
	"id" => "email",
	"type" => "text",
	"class" => "form-campo",
	"maxlength" => "50",
	"value" => set_value("email", "")
);

$login_array_to_form = array(
	"name" => "login",
	"id" => "login",
	"type" => "text",
	"class" => "form-campo",
	"maxlength" => "20",
	"value" => set_value("login", "")
);

$password_array_to_form = array(
	"name" => "senha",
	"id" => "senha",
	"class" => "form-campo",
	"maxlength" => "255"
);

$submit_button_array_to_form = array(
	"class" => "btn btn-primary",
	"content" => "Cadastrar",
	"type" => "submit"
);

echo form_open("usuario/novo");

	// Name field
	echo form_label("Nome", "nome");
	echo form_input($name_array_to_form);
	echo form_error("nome");
	
	// CPF field
	echo form_label("CPF", "cpf");
	echo form_input($cpf_array_to_form);
	echo form_error("cpf");

	// E-mail field
	echo form_label("E-mail", "email");
	echo form_input($email_array_to_form);
	echo form_error("email");

	// User type field
	echo form_label("Tipo de Usuário", "userType"); 
	echo "<br>";
	echo form_dropdown("userType",$form_user_types);
	echo "* Para adicionar mais de um tipo, acesse o menu para editar usuário";
	echo form_error("userType");

	// Login field
	echo "<br>";
	echo form_label("Login", "login");
	echo form_input($login_array_to_form);
	echo form_error("login");

	// Password field
	echo form_label("Senha", "senha");
	echo form_password($password_array_to_form);
	echo form_error("senha");

	// Submit button
	echo "<br>";
	echo form_button($submit_button_array_to_form);

echo form_close();
?>
