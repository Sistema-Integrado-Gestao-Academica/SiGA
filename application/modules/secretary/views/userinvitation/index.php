
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
      "name" => "email_to_invite",
      "id" => "email_to_invite",
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
        <?= form_label("Convidar usuário para ser:", "invitation_profiles"); ?>
        <?= form_dropdown("invitation_profiles", $invitationGroups, '', "class='form-control'"); ?>
      </div>

      <div class='form-group'>
        <?= form_label("E-mail para enviar o convite", "email_to_invite"); ?>
        <?= form_input($emailToInvite); ?>
      </div>
    </div>

    <div class='footer body bg-gray'>
    <?= form_button($submitBtn);?>
    </div>
  </div>
<?= form_close(); ?>
