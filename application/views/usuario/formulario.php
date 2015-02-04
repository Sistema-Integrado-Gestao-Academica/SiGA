<div class="form-box" id="login-box"> 
	<div class="header">Cadastrar um novo usuário</div>
	<?= form_open("usuario/novo") ?>
		<div class="body bg-gray">
			<div class="form-group">
				<?= form_label("Nome", "nome") ?>
				<?= form_input(array(
					"name" => "nome",
					"id" => "nome",
					"type" => "text",
					"class" => "form-campo",
					"maxlength" => "70",
					"class" => "form-control",
					"value" => set_value("nome", "")
				)) ?>
				<?= form_error("nome") ?>
			</div>
			
			<div class="form-group">
				<?= form_label("CPF", "cpf") ?>
				<?= form_input(array(
					"name" => "cpf",
					"id" => "cpf",
					"type" => "text",
					"class" => "form-campo",
					"maxlength" => "11",
					"value" => set_value("cpf", ""),
					"class" => "form-control",
					"placeholder" => "Somente Números"
				)) ?>
				<?= form_error("cpf") ?>
			</div>
				
			<div class="form-group">
				<?= form_label("E-mail", "email") ?>
				<?= form_input(array(
					"name" => "email",
					"id" => "email",
					"type" => "text",
					"class" => "form-campo",
					"maxlength" => "50",
					"class" => "form-control",
					"value" => set_value("email", "")
				)) ?>
				<?= form_error("email") ?>
			</div>

			<div class="form-group">
				<?= form_label("Tipo de Usuário", "userGroup"); ?><br>
				<?= form_dropdown("userGroup", $user_groups) ?>
				<br>
				<?= "* Para adicionar mais de um grupo, contate o administrador" ?>
				<?= form_error("userGroup") ?>
			</div>

			<div class="form-group">
				<?= form_label("Login", "login") ?>
				<?= form_input(array(
					"name" => "login",
					"id" => "login",
					"type" => "text",
					"class" => "form-campo",
					"maxlength" => "20",
					"class" => "form-control",
					"value" => set_value("login", "")
				)) ?>
				<?= form_error("login") ?>
			</div>

			<div class="form-group">
				<?= form_label("Senha", "senha") ?>
				<?= form_password(array(
					"name" => "senha",
					"id" => "senha",
					"class" => "form-campo",
					"class" => "form-control",
					"maxlength" => "255"
				)) ?>
				<?= form_error("senha") ?>
			</div>
		</div>

		<div class="footer">
			<?= form_button(array(
				"class" => "btn bg-olive btn-block",
				"content" => "Cadastrar",
				"type" => "submit"
			)) ?>
		</div>
	<?= form_close() ?>
</div>
