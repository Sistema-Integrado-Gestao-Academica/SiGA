<div class="form-box" id="login-box"> 
<br><br>
<div class="callout callout-green">
    <p> Enviaremos para o seu email a sua nova senha. </p>
    <p> Digite o email utilizado no cadastro. </p>
</div>
    <div class="header"></div>
    <?= form_open("auth/userController/restorePassword") ?>
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
            </div>
        </div>
        <div class="footer">
            <?= form_button(array(
                "class" => "btn bg-olive btn-block",
                "content" => "Recuperar senha",
                "type" => "submit"
            )) ?>
        </div>
    <?= form_close() ?>
</div>
