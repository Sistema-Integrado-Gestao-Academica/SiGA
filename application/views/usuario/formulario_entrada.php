
<?php 

$user = new Usuario();

// All user types registered on DB
$form_user_groups = $user->getAllowedUserGroupsForNotLoggedRegistration();

$name_array_to_form = array(
	"name" => "nome",
	"id" => "nome",
	"type" => "text",
	"class" => "form-campo",
	"maxlength" => "70",
	"class" => "form-control",
	"value" => set_value("nome", "")
);

$cpf_array_to_form = array(
		"name" => "cpf",
		"id" => "cpf",
		"type" => "text",
		"class" => "form-campo",
		"maxlength" => "11",
		"value" => set_value("cpf", ""),
		"class" => "form-control",
		"placeholder" => "Somente Números"
);

$email_array_to_form = array(
	"name" => "email",
	"id" => "email",
	"type" => "text",
	"class" => "form-campo",
	"maxlength" => "50",
	"class" => "form-control",
	"value" => set_value("email", "")
);

$login_array_to_form = array(
	"name" => "login",
	"id" => "login",
	"type" => "text",
	"class" => "form-campo",
	"maxlength" => "20",
	"class" => "form-control",
	"value" => set_value("login", "")
);

$password_array_to_form = array(
	"name" => "senha",
	"id" => "senha",
	"class" => "form-campo",
	"class" => "form-control",
	"maxlength" => "255"
);

$submit_button_array_to_form = array(
	"class" => "btn bg-olive btn-block",
	"content" => "Cadastrar",
	"type" => "submit"
);
?>
<div class="form-box" id="login-box"> 
	<div class="header">Cadastro de um novo usuário</div>
	<?php
	echo form_open("usuario/novo");
	?>
	<div class="body bg-gray">
		<div class="form-group">
				<?php 
				// Name field
				echo form_label("Nome", "nome");
				echo form_input($name_array_to_form);
				echo form_error("nome");
				?>
			</div>	
		
		<div class="form-group">
			<?php 
			// CPF field
			echo form_label("CPF", "cpf");
			echo form_input($cpf_array_to_form);
			echo form_error("cpf");
			?>
		</div>	
			
		<div class="form-group">
			<?php	
			// E-mail field
			echo form_label("E-mail", "email");
			echo form_input($email_array_to_form);
			echo form_error("email");
			?>
		</div>	
				
		<div class="form-group">
			<?php
			// User type field
			echo form_label("Tipo de Usuário", "userGroup"); 
			echo "<br>";
			echo form_dropdown("userGroup", $form_user_groups);
			echo "* Para adicionar mais de um tipo, acesse o menu para editar usuário";
			echo form_error("userGroup");
			?>
		</div>	
				
		<div class="form-group">
			<?php
			// Login field
			echo form_label("Login", "login");
			echo form_input($login_array_to_form);
			echo form_error("login");
			?>
		</div>	
				
		<div class="form-group">
			<?php
			// Password field
			echo form_label("Senha", "senha");
			echo form_password($password_array_to_form);
			echo form_error("senha");
			?>
		</div>		
	</div>
	<div class="footer">
		<?php 
		// Submit button
		echo "<br>";
		echo form_button($submit_button_array_to_form);
		echo form_close();
		?>
	</div>
	
</div>
