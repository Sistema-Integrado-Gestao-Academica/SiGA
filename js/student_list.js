$(document).ready(function(){

	$("#enrollment_btn").click(function(e){
		e.preventDefault();
		searchStudentsOnListByEnrollment();
	});


	$("#name_btn").click(function(e){
		e.preventDefault();
		searchStudentsOnListByName();
	});

	$("#student_enrollment_field").keypress(function(){
		searchStudentsOnListByEnrollment();
	});

	$("#student_name_field").keypress(function(){
		searchStudentsOnListByName();
	});
	
	const ORDER_BY_NAME = 'name';
	const ORDER_BY_ENROLLMENT = 'enrollment';
	const ORDER_BY_DATE = 'enroll_date';

	(function($) {

	  orderByName = function(data) {
	  	ids = data.split(","); 
	  	courseId = ids.shift();
	  	orderStudentsOnList(ORDER_BY_NAME, ids, courseId);
	  };
	})(jQuery);

	(function($) {

	  orderByEnrollment = function(data) {
	  	ids = data.split(","); 
	  	courseId = ids.shift();
	  	orderStudentsOnList(ORDER_BY_ENROLLMENT, ids, courseId);
	  };
	})(jQuery);

	(function($) {

	  orderByDate = function(data) {
	  	ids = data.split(","); 
	  	courseId = ids.shift();
	  	orderStudentsOnList(ORDER_BY_DATE, ids, courseId);
	  };
	})(jQuery);
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
			e.preventDefault();
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
			return false;
		}
	);
}

function orderStudentsOnList(type, studentsIds, courseId){

	var siteUrl = $("#site_url").val();

	var urlToPost = siteUrl + "/secretary/ajax/secretaryajax/orderStudentsOnList";
	$.post(
		urlToPost,
		{
			type: type,
			studentsIds: studentsIds,
			courseId: courseId
		},
		function(data){
			$("#students_list_table").html(data);
		}
	);
}
