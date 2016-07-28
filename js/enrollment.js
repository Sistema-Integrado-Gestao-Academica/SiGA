$(document).ready(function(){

	$("#solicit_inscription").click(function(){
		solicitCourseForGuest();
	});

	$("#guest_name").keypress(function(){
		searchGuestsToEnroll();
	});

	$("#search_guests_btn").click(function(){
		searchGuestsToEnroll();
	});

	$("#discipline_search_btn").ready(function(){
		searchDisciplineClasses();
	});

	$("#discipline_search_btn").click(function(){
		searchDisciplineClasses();
	});

	$("#discipline_name_search").on('input', function(event){
		searchDisciplineClasses();
	});

	$("#confirm_enrollment_request_btn").click(function(event){
		var confirmed = confirm("Confirma a solicitação de matrícula?");
		if(!confirmed){
			event.preventDefault();
		}
	});

});


function searchGuestsToEnroll(){

	var guestName = $("#guest_name").val();
	var course = $("#course").val();

	var siteUrl = $("#site_url").val();

	var urlToPost = siteUrl + "/secretary/ajax/enrollmentajax/searchGuestUsersToEnroll";

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

function searchDisciplineClasses(){

	var disciplineName = $("#discipline_name_search").val();
	var courseId = $("#courseId").val();
	var userId = $("#userId").val();
	var siteUrl = $("#site_url").val();

	var urlToPost = siteUrl + "/secretary/ajax/enrollmentajax/searchDisciplinesToRequest";

	$.post(
		urlToPost,
		{
			disciplineName: disciplineName,
			courseId: courseId,
			userId: userId
		},
		function(data){
			$("#discipline_search_result").html(data);
		}
	);
}

function solicitCourseForGuest(){

	var courseId = $('#courses_name option:selected').val();
	var courseName = $('#courses_name option:selected').text();
	var siteUrl = $("#site_url").val();
	var urlToPost = siteUrl + "/secretary/ajax/enrollmentajax/courseForGuest";
	$.post(
		urlToPost,
		{
			courseId: courseId,
			courseName: courseName
		},
		function(data){
			$("#choosen_course").html(data);
		}
	);
}