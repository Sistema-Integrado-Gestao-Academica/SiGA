$(document).ready(function(){

  // Hide error message
  $("#notification_error_warn").hide();

  $("#user_name").on('input', function(){
      searchUsersToNotify();
  });

  $("#notify_group_of_users_btn").click(function(event){
    var teachersIsChecked = $('#notify_teachers').is(":checked");
    var studentsIsChecked = $('#notify_students').is(":checked");

    // At least one of them must be notified
    if(!teachersIsChecked && !studentsIsChecked){
      $("#notification_error_warn").show();
      event.preventDefault();
    }

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