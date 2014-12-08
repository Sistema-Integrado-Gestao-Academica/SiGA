
$(document).ready(function(){
	getChoosenCourseType();

	$("#courseType").change(function(){
		getChoosenCourseType();
	});
});

function getChoosenCourseType(){

	var siteUrl = $("#site_url").val();
	var choosenCourseType = $("#courseType").val();

	evaluatesCourseType(choosenCourseType, siteUrl);
}

function evaluatesCourseType(choosenCourseType, siteUrl){
	
	var urlToPost = siteUrl+"/course/checkChoosenCourseType";
	$.post(
		urlToPost,
		{courseType: choosenCourseType}, 
		function(data){
			$("#post_grad_types").html(data);
		}
	);
}

function apagar_conta() {
	if (!confirm("Tem certeza que deseja apagar sua conta?"))
		return false;
}
