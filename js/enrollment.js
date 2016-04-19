$(document).ready(function(){

	$("#guest_name").keypress(function(){
		searchGuestsToEnroll();
	});

	$("#search_guests_btn").click(function(){
		searchGuestsToEnroll();
	});
});


function searchGuestsToEnroll(){

	var guestName = $("#guest_name").val();
	var course = $("#course").val();

	var siteUrl = $("#site_url").val();

	var urlToPost = siteUrl + "/ajax/enrollmentajax/searchGuestUsersToEnroll";

	$.post(
		urlToPost,
		{
			guestName: guestName,
			course: course
		},
		function(data){
			$("#guests_table").html(data);
		}
	);
}