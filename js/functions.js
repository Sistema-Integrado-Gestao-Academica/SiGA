
$(document).ready(function(){
	evaluatesCourseType();

	$("#courseType").change(function(){
		evaluatesCourseType();
	});

});

function evaluatesCourseType(){
	
	var choosenCourseType = getChoosenCourseType();

	var urlToPost = choosenCourseType.siteUrl+"/course/checkChoosenCourseType";
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
	var urlToPost = choosenPostGraduationType.siteUrl + "/course/checkChoosenPostGraduationType";
	
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

function evaluatesProgram(){
	var choosenProgram = getChoosenPostGradType();
	var urlToPost = choosenProgram.siteUrl + "/course/checkChoosenProgram";

	$.post(
		urlToPost,
		{program: choosenProgram.postGradType},
		function(data){

			$("#chosen_post_grad_type_update").html(data);

			evaluatesAcademicProgram();
			$("#academic_program_types").change(function(){
				evaluatesAcademicProgram();
			});
		}

	);
}

function evaluatesAcademicProgram(){
	var choosenProgram = getChoosenAcademicProgram();
	var urlToPost = choosenProgram.siteUrl + "/course/checkChoosenAcademicProgram";

	$.post(
		urlToPost,
		{academicProgram: choosenProgram.academicProgram},
		function(data){
			$("#choosen_program").html(data);
		}

	);
}

function getChoosenAcademicProgram(){
	var siteUrl = $("#site_url").val();
	var choosenAcademicProgram = $("#academic_program_types").val();

	var choosenProgram = {
		siteUrl: siteUrl,
		academicProgram: choosenAcademicProgram
	};

	return choosenProgram;
}

function apagar_conta() {
	if (!confirm("Tem certeza que deseja apagar sua conta?"))
		return false;
}
