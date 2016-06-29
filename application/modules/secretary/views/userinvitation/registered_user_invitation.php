
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
      "id" => "confirm_invite_btn",
      "name" => "confirm_invite_btn",
      "type" => "submit",
      "content" => "Convidar",
      "class" => "btn btn-lg bg-olive btn-block"
  );

  $emailToInvite = array(
      "name" => "email_to_invite_",
      "id" => "email_to_invite_",
      "type" => "text",
      "class" => "form-campo",
      "class" => "form-control",
      "maxlength" => "50",
      "required" => TRUE,
      "value" => $userToInvite->getEmail(),
      "disabled" => TRUE
  );

  $sendEmail = array(
    'name' => 'send_invitation_email_confirm',
    'id' => 'send_invitation_email_confirm',
    'value' => TRUE,
    'checked' => FALSE,
    'required' => TRUE
  );
?>

<?= form_open("invite");?>
  
  <?= form_hidden('email_to_invite', $userToInvite->getEmail()) ?>

  <div class='form-box'>
    <div class='header'>Convidar usuário para o sistema</div>

    <div class='body bg-gray'>
      <div class='form-group'>
        <?php if(!empty($invitationGroups)){ ?>
          <?= form_label("Convidar usuário para ser:", "invitation_profiles"); ?>
          <?= form_dropdown("invitation_profiles", $invitationGroups, '', "class='form-control'"); ?>
          <?= form_error("invitation_profiles");?>
        <?php 
              }else{
                $submitBtn["disabled"] = TRUE;
                callout("danger", "Este usuário já participa dos grupos possíveis para convite.");
              }
        ?>
      </div>

      <div class='form-group'>
        <?= form_label("E-mail para enviar o convite", "email_to_invite"); ?>
        <?= form_input($emailToInvite); ?>
        <?= form_error("email_to_invite");?>
      </div>

      <div class='form-group'>
        <?= form_checkbox($sendEmail); ?>
        <?= form_label("Clique aqui para confirmar o convite", "send_invitation_email_confirm"); ?>
        <?= form_error("send_invitation_email_confirm");?>
      </div>
      
      <div class='form-group'>
        <div class='alert alert-info'>
          <i class='fa fa-info'></i>
          <p>O usuário <?= $userToInvite->getName(); ?> já é um usuário do sistema.</p>
          <p>Este usuário é participante dos grupos: 
            <?php
              foreach($userGroups as $group){
                echo "<br>- ";
                echo bold(ucfirst($group['group_name']));
                echo "";
              } 
            ?>.
          </p>
          <p>Caso envie este convite, você estará o convidando para participar deste novo grupo. Os grupos que este usuário já participa foram removidos das opções</p>.
        </div>
      </div>
    </div>

    <div class='footer body bg-gray'>
    <?= form_button($submitBtn);?>
    </div>
  </div>
<?= form_close(); ?>

<script src=<?=base_url("js/invitation.js")?>></script>
