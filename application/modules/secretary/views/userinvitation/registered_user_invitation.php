<br><br><br>
<?php 
  $submitBtn = array(
      "id" => "confirm_invite_btn",
      "name" => "confirm_invite_btn",
      "type" => "submit",
      "content" => "Convidar",
      "class" => "btn btn-lg bg-olive btn-block"
  );

  $emailsToInvite = array(
      "name" => "emails_to_invite_",
      "id" => "emails_to_invite_",
      "type" => "text",
      "class" => "form-campo",
      "class" => "form-control",
      "required" => TRUE,
      "value" => $emails,
      "disabled" => TRUE
  );

  $sendEmail = array(
    'name' => 'send_invitation_email_confirm',
    'id' => 'send_invitation_email_confirm',
    'value' => TRUE,
    'checked' => FALSE,
    'required' => TRUE
  );

if(!empty($usersInGroup)){
  
  echo "<div class='alert alert-danger'>";
  echo "<i class='fa fa-info'></i>";
      $allUsersInGroup = "";
      foreach ($usersInGroup as $email) {
          $allUsersInGroup .= $email.",";
      }
      $allUsersInGroup = substr($allUsersInGroup, 0, -1);

      if(strpos($allUsersInGroup, ",")){ 
        echo "<p>Os emails {$allUsersInGroup} já são de usuários do sistema que estão no grupo solicitado.</p>";
        echo "<p>O convite não será enviado para eles.</p>";
      }
      else{
        echo "<p>O email {$allUsersInGroup} já é de um usuário do sistema que está no grupo solicitado.</p>";
        echo "<p>O convite não será enviado para ele.</p>";
      }
  echo "</div>";
} ?>


<?= form_open("invite");?>
  
  <?= form_hidden('emails_to_invite', $emails) ?>

  <div class='form-box'>
    <div class='header'>Convidar usuário(s) para o sistema</div>

    <div class='body bg-gray'>
      <div class='form-group'>
          <?= form_label("Convidar usuários para ser:", "invitation_profiles"); ?>
          <?= form_dropdown("invitation_profiles", $invitationGroups, $invitationGroup, "class='form-control'"); ?>
          <?= form_error("invitation_profiles");?>
      </div>

      <?php if(strlen($emails) > 50){ ?>
        <?= form_textarea($emailsToInvite); ?>
      <?php  }
      else{?>
        <?= form_input($emailsToInvite); ?>
      <?php }?>
      <div class='form-group'>
        <?= form_label("E-mail para enviar o convite", "emails_to_invite"); ?>
        <?= form_error("emails_to_invite");?>
      </div>

      <div class='form-group'>
        <?= form_checkbox($sendEmail); ?>
        <?= form_label("Clique aqui para confirmar o convite", "send_invitation_email_confirm"); ?>
        <?= form_error("send_invitation_email_confirm");?>
      </div>
      
      <?php
        if(!empty($usersToInvite)){
          echo "<div class='form-group'>";
            echo "<div class='alert alert-info'>";
              echo "<i class='fa fa-info'></i>";
        
            $allUsersToInvite = "";
            foreach ($usersToInvite as $user) {
              $allUsersToInvite .= $user->getEmail().",";
            }
            $allUsersToInvite = substr($allUsersToInvite, 0, -1);

            if(strpos($allUsersToInvite, ",")){ 
              echo "<p>Os emails {$allUsersToInvite} já pertencem a usuários do sistema de outro grupo.</p>";
              echo "<p>Caso envie este convite, você estará os convidando para participar deste novo grupo.</p>";
            }
            else{
              echo "<p>O email {$allUsersToInvite} já pertence a um usuário do sistema de outro grupo.</p>";
              echo "<p>Caso envie este convite, você estará o convidando para participar deste novo grupo.</p>";
            } 
            echo "</div>";
          echo "</div>";
        echo "</div>";
      } ?>

    <div class='footer body bg-gray'>
    <?= form_button($submitBtn);?>
    </div>
  </div>
<?= form_close(); ?>

<script src=<?=base_url("js/invitation.js")?>></script>
