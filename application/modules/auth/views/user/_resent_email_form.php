<div class="form-box" id="login-box"> 
	<div class="header"></div>
		<?= form_open("auth/useractivation/resentEmail") ?>
    	<div class="body bg-gray">
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
        </div>
		</div>
    <div class="footer">
        <?= form_button(array(
            "class" => "btn bg-olive btn-block",
            "content" => "Reenviar E-mail",
            "type" => "submit"
        )) ?>
    </div>
	<?= form_close() ?>
</div>