
<div class="row">
  <div class="col-lg-4">
    <?php
      $hidden = array(
        "userInvitation" => $userInvitation
      );

      echo Modules::run("auth/userController/register", $groups, $invitedEmail, $hidden);
    ?>
  </div>
</div>