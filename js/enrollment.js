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

	$("#finalize_request").click(function(event){
		alert("fdsfad");
		var confirmed = confirm("Deseja finalizar a solicitação?! \n\n\
		Após finalizar a solicitação não é possível recusar ou aprovar disciplinas. O aluno também não poderá mais alterar sua solicitação.");
		if(!confirmed){
			event.preventDefault();
		}
	});

	$("#confirm_enrollment_request_btn").click(function(event){
		var confirmed = confirm("Confirma a solicitação de matrícula?");
		if(!confirmed){
			event.preventDefault();
		}
	});
});

function updateEnrollDiscipline(event, requestId, offerDiscipline, requestedOn, requestingArea, approval){

	event.preventDefault();

	var siteUrl = $("#site_url").val();
	var urlToPost = siteUrl + "/secretary/ajax/enrollmentajax/updateEnrollDisciplineStatus";

	$.post(
		urlToPost,
		{
			request_id: requestId,
			offer_discipline: offerDiscipline,
			requested_on: requestedOn,
			approval: approval,
			requesting_area: requestingArea,
		},
		function(data){
			$("#solicitation_details_"+requestId).html(data);
		}
	);
}

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
	var isUpdate = $("#is_update").val();
	var requestId = $("#request").val();
	var courseId = $("#courseId").val();
	var userId = $("#userId").val();
	var siteUrl = $("#site_url").val();

	var urlToPost = siteUrl + "/secretary/ajax/enrollmentajax/searchDisciplinesToRequest";

	$.post(
		urlToPost,
		{
			disciplineName: disciplineName,
			courseId: courseId,
			userId: userId,
			requestId: requestId,
			isUpdate: isUpdate
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
