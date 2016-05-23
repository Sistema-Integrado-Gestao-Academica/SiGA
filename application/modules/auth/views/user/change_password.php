<div class="form-box" id="login-box"> 
    <div class="header">Definir uma nova senha</div>
    <?= form_open("auth/userController/changePassword") ?>
        <div class="body bg-gray">
            <div class="form-group">
                <?= form_label("Digite sua nova senha", "password") ?>
                <?= form_password(array(
					"name" => "password",
					"id" => "password",
					"class" => "form-control",
					"maxlength" => "255"
				))?>
                <?= form_error("password") ?>
            </div>
            <div class="form-group">
                <?= form_label("Confirme sua nova senha", "confirm_password") ?>
                <?= form_password(array(
					"name" => "confirm_password",
					"id" => "confirm_password",
					"class" => "form-control",
					"maxlength" => "255"
				))?>
                <?= form_error("password") ?>
            </div>
        </div>
        <div class="footer">
            <?= form_button(array(
                "class" => "btn bg-olive btn-block",
                "content" => "Confirmar",
                "type" => "submit"
            )) ?>
        </div>
    <?= form_close() ?>
</div>

