<?php
    $name = array(
        "name" => "user_name",
        "id" => "user_name",
        "type" => "text",
        "class" => "form-control",
        "placeholder" => "Informe o nome da pessoa para pesquisa.",
        "maxlength" => "70"
    );
?>

<div class="row">
  <div class="col-md-4 col-sm-6">
    <h4><i class='fa fa-search-plus'></i> Pesquise aqui os usuários para notificar</h4>
    <div class='form-group'>
      <?= form_input(array(
            "id" => "project_id",
            "type" => "hidden",
            // "value" => $project['id']
          )) ?>
      <?= form_label("Nome:", "user_name") ?>
      <?= form_input($name) ?>
    </div>
  </div>
  <div class="col-md-8 col-sm-6">
    <div id="users_to_notify_list" style="height: 300px; overflow-y: scroll;"></div>
    <br>
  </div>
</div>
<br>

<div id="notify_user_modal"></div>

<!-- <script src=<?=base_url("js/project.js")?>></script> -->
<script type="text/javascript">
  $(document).ready(function(){

    $("#user_name").on('input', function(){
        searchUsersToNotify();
    });

  });

  function searchUsersToNotify(){
    var user = $("#user_name").val();
    var siteUrl = $("#site_url").val();

    var urlToPost = siteUrl + "/notification/userNotification/getUsersToNotify";

    $.post(
      urlToPost,
      {user: user},
      function(data){
        $("#users_to_notify_list").html(data);
      }
    );
  }

  function showNotifyUserModal(userId){

    var siteUrl = $("#site_url").val();

    var urlToPost = siteUrl + "/notification/userNotification/getNotifyUserModal";

    $.post(
      urlToPost,
      {user: userId},
      function(data){
        $("#notify_user_modal").html(data);
        $("#notify_user_" + userId + "_modal").modal('show');
      }
    );
  }
</script>