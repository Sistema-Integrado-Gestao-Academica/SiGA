$(document).ready(function(){

	$("#documentType").change(function(){
		checkDocumentType();
	});
});

function checkDocumentType(){

	var currentType = $("#documentType").val();
	var siteUrl = $("#site_url").val();

	var urlToPost = siteUrl + "/student/ajax/requestajax/checkDocumentType";

	$.post(
		urlToPost,
		{documentType: currentType},
		function(data){
			$("#document_request_data").html(data);
		}
	);
}