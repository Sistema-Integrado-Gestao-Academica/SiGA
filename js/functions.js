
$(document).ready(function(){

	$("#search_student_btn").click(function(){
		searchForStudent();
	});

	$("#approve_offer_list_btn").hover(function(){
		$("#approve_offer_list_btn").popover('show');
	},function(){
		$("#approve_offer_list_btn").popover('hide');
	});

	$("#remove_program_btn").hover(function(){
		$("#remove_program_btn").popover('show');
	},function(){
		$("#remove_program_btn").popover('hide');
	});

	$("#edit_program_btn").hover(function(){
		$("#edit_program_btn").popover('show');
	},function(){
		$("#edit_program_btn").popover('hide');
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

function getCurrentCourse(){
	var currentCourse = $("#current_course").val();

	return currentCourse;
}

function deleteAccount() {
	return confirm("Tem certeza que deseja apagar sua conta?");
}

function passwordRequest() {
	var password = prompt("Digite sua senha para continuar")
	document.getElementsByName("password")[0].value = password;
}
