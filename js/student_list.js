$(document).ready(function(){

	$("#search_student_enrollment_on_list_btn").click(function(){
		searchStudentsOnListByEnrollment();
	});


	$("#search_student_name_on_list_btn").click(function(){
		searchStudentsOnListByName();
	});


	$("#student_enrollment_field").keypress(function(){
		searchStudentsOnListByEnrollment();
	});


	$("#student_name_field").keypress(function(){
		searchStudentsOnListByName();
	});
	
});


function searchStudentsOnListByEnrollment(){

	var enrollment = $("#student_enrollment_field").val();
	var course = $("#course").val();

	var siteUrl = $("#site_url").val();

	var urlToPost = siteUrl + "/secretary/ajax/secretaryajax/searchStudentsByEnrollment";
	$.post(
		urlToPost,
		{
			enrollment: enrollment,
			course: course
		},
		function(data){
			$("#students_list_table").html(data);
		}
	);
}

function searchStudentsOnListByName(){

	var name = $("#student_name_field").val();
	var course = $("#course").val();

	var siteUrl = $("#site_url").val();

	var urlToPost = siteUrl + "/secretary/ajax/secretaryajax/searchStudentsByName";
	$.post(
		urlToPost,
		{
			name: name,
			course: course
		},
		function(data){
			$("#students_list_table").html(data);
		}
	);
}

