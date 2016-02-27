<?php
$sessao = $this->session->userdata("current_user");
require_once APPPATH.'controllers/capesavaliation.php';
if ($sessao != NULL) { ?>
	<p class="alert alert-success text-center">Logado como "<?=$sessao['user']['login']?>"</p>
	<h1 class="bemvindo">Bem vindo!</h1>

	<?php

	 if ($sessao['user']['login'] == 'admin'){
	 	$admin = new CapesAvaliation();

	 	$atualizations = $admin->getCapesAvaliationsNews();

	 	showCapesAvaliationsNews($atualizations);
	 }
		?>



<?php } else { ?>
	# Nessa página mostrar os paranaue do curso
	<h1 class="bemvindoLogin">Bem vindo ao SiGA</h1>



	<div class="form-box" id="login-box">
		<div class="header">Login</div>
		<?php
		echo form_open("login/autenticar");
		?>
		<div class="body bg-gray">
			<div class="form-group">
				<?php
				echo form_label("Login", "login");
				echo form_input(array(
					"name" => "login",
					"id" => "login",
					"type" => "text",
					"class" => "form-campo",
					"maxlength" => "255",
					"value" => set_value("login", ""),
					"class" => "form-control",
					"placeholder" => "Login de Usuário"
				));
				?>
			</div>
			<div class="form-group">
				<?php
					echo form_label("Senha", "senha");
					echo form_input(array(
						"name" => "senha",
						"id" => "senha",
						"type" => "password",
						"class" => "form-campo",
						"maxlength" => "255",
						"class" => "form-control",
						"placeholder" => "Senha"
					));
				?>
			</div>
			<br>
		</div>
		<div class="footer">
			<?php
				echo form_button(array(
					"id" => "login_btn",
					"class" => "btn bg-olive btn-block",
					"content" => "Entrar",
					"type" => "submit"
				));

				echo form_close();
			} ?>
		</div>
	</div>
