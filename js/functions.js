
$(document).ready(function(){
	
	evaluatesCourseType();

	$("#courseType").change(function(){
		evaluatesCourseType();
	});

	$("#search_student_btn").click(function(){
		searchForStudent();
	});

});

// Student functions
function searchForStudent(){
	var studentName = $("#student_name").val();
	var siteUrl = $("#site_url").val();

	var urlToPost = siteUrl + "usuario/searchForStudent";
	$.post(
		urlToPost,
		{student_name: studentName},
		function(data){
			$("#search_student_result").html(data);
		}
	);
}

// Course functions
function evaluatesCourseType(){
	
	var choosenCourseType = getChoosenCourseType();

	var urlToPost = choosenCourseType.siteUrl + "course/checkChoosenCourseType";
	$.post(
		urlToPost,
		{courseType: choosenCourseType.courseType}, 
		function(data){
			$("#post_grad_types").html(data);

			evaluatesPostGraduationType();
			$("#post_graduation_type").change(function(){
				evaluatesPostGraduationType();
			});
		}
	);
}

function getChoosenCourseType(){

	var siteUrl = $("#site_url").val();
	var choosenCourseType = $("#courseType").val();

	var choosenType = {
		siteUrl: siteUrl,
		courseType: choosenCourseType
	};

	return choosenType;
}

function evaluatesPostGraduationType(){

	var choosenPostGraduationType = getChoosenPostGradType();
	var urlToPost = choosenPostGraduationType.siteUrl + "course/checkChoosenPostGraduationType";
	
	$.post(
		urlToPost,
		{postGradType: choosenPostGraduationType.postGradType},
		function(data){
			$("#chosen_post_grad_type").html(data);

			evaluatesProgram();
			$("#post_graduation_type").change(function(){
				evaluatesProgram();
			});
		}
	);
}

function getChoosenPostGradType(){
	var siteUrl = $("#site_url").val();
	var choosenPostGraduationType = $("#post_graduation_type").val();

	var choosenType = {
		siteUrl: siteUrl,
		postGradType: choosenPostGraduationType
	};

	return choosenType;
}

function getCurrentCourse(){
	var currentCourse = $("#current_course").val();

	return currentCourse;
}

function evaluatesProgram(){
	var choosenProgram = getChoosenPostGradType();
	var urlToPost = choosenProgram.siteUrl + "course/checkChoosenProgram";
	var currentCourse = getCurrentCourse();

	$.post(
		urlToPost,
		{program: choosenProgram.postGradType, course: currentCourse},
		function(data){

			$("#registered_master_degree").html(data);

			displayMasterDegreeForm();

			// evaluatesAcademicProgram();
			// $("#academic_program_types").change(function(){
			// 	evaluatesAcademicProgram();
			// });

		}

	);
}

function displayMasterDegreeForm(){

	var choosenProgram = getChoosenPostGradType();
	var urlToPost = choosenProgram.siteUrl + "course/displayMasterDegreeUpdateForm";

	$.post(
		urlToPost,
		{program: choosenProgram.postGradType},
		function(data){

			$("#update_master_degree").html(data);

			displayRegisteredDoctorate();
		}
	);
}

function displayRegisteredDoctorate(){
	var choosenProgram = getChoosenPostGradType();
	var urlToPost = choosenProgram.siteUrl + "course/displayRegisteredDoctorate";
	var currentCourse = getCurrentCourse();

	$.post(
		urlToPost,
		{program: choosenProgram.postGradType, course: currentCourse},
		function(data){
			$("#registered_doctorate").html(data);
		}
	);
}

function deleteAccount() {
	return confirm("Tem certeza que deseja apagar sua conta?");
}

