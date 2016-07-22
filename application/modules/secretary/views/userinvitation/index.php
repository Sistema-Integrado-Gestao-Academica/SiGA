
<br>
<br>
<div class="row">
  <div class="col-md-10">
    <div class='alert alert-info'>
      <i class='fa fa-info'></i>
      <b>
        <p>Aqui você pode convidar alguém para se cadastrar no sistema!</p>
        <p>O convite será enviado por e-mail. </p>
        <p>Você pode escolher um grupo específico para que o novo usuário se cadastre.</p>
      </b>
    </div>
  </div>
</div>

<?php 
  $submitBtn = array(
      "type" => "submit",
      "content" => "Convidar",
      "class" => "btn btn-lg bg-olive btn-block"
  );

  $emailToInvite = array(
      "name" => "emails_to_invite",
      "id" => "emails_to_invite",
      "type" => "text",
      "class" => "form-campo",
      "class" => "form-control",
      "maxlength" => "50",
      "required" => TRUE
  );
?>

<?= form_open("invite");?>
  
  <div class='form-box'>
    <div class='header'>Convidar usuário para o sistema</div>

    <div class='body bg-gray'>
      <div class='form-group'>
        <?= form_label("Convidar usuário(s) para ser:", "invitation_profiles"); ?>
        <?= form_dropdown("invitation_profiles", $invitationGroups, '', "class='form-control'"); ?>
        <?= form_error("invitation_profiles");?>
      </div>

      <div class='form-group'>
        <?= form_label("E-mail(s) para enviar o convite", "email_to_invite"); ?>
        <?= form_input($emailToInvite); ?>
        <?= form_error("emails_to_invite");?>
        <br>
        <div class="alert alert-info alert-dismissible" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <i class='fa fa-info'></i>
          <b>
            <p class="text">Você pode adicionar vários emails para enviar o convite. Basta colocar ";" entre um email e outro.</p>
            <p>Exemplo: </p>
            <p><small>exemplo1@exemplo.com;exemplo2@exemplo.com </small></p>
          </b>
        </div>
      </div>
    </div>

    <div class='footer body bg-gray'>
    <?= form_button($submitBtn);?>
    </div>
  </div>
<?= form_close(); ?>
