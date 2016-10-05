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