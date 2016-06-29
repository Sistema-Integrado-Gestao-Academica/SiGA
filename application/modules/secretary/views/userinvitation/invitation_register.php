
<br>
<br>
<div class="row">
  <div class="col-md-10">
    <div class='alert alert-info'>
      <i class='fa fa-info'></i>
      <b>
        <p>Você recebeu um convite para se cadastrar no sistema como <i>
          <?php
            foreach($groups as $group){
              echo $group;
              if(count($groups) > 1){
                echo ", ";
              }
            }
          ?>
        </i> !</p>
        <p>Informe seus dados abaixo e cadastre-se!</p>
      </b>
    </div>
  </div>
</div>

<?php
  $hidden = array(
    "userInvitation" => $userInvitation
  );

  echo Modules::run("auth/userController/register", $groups, $invitedEmail, $hidden);
?>