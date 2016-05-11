<div class="form-box" id="login-box"> 
<div class="callout callout-green">
    <p> Se realmente desejar cancelar seu cadastro digite sua senha</p>
</div>
	<div class="header"></div>
		<?= form_open("useractivation/cancelRegister") ?>
    	<div class="body bg-gray">
        <div class="form-group">
			<?= form_label("Senha", "password") ?>
			<?= form_password(array(
				"name" => "password",
				"id" => "password",
				"class" => "form-campo",
				"class" => "form-control",
				"maxlength" => "255"
			)) ?>
			<?= form_error("password") ?>
			<?= form_hidden("id", $user['id'])?>
			<?= form_hidden("login", $user['login'])?>
        </div>
		</div>
    <div class="footer">
        <?= form_button(array(
            "class" => "btn bg-olive btn-block",
            "content" => "Cancelar cadastro",
            "type" => "submit"
        )) ?>
    </div>
	<?= form_close() ?>
</div>