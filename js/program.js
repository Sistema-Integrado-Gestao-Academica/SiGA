
$(document).ready(function(){

	$('#add_field_file_form').submit(function(e) {
		alert("veeei");
    	e.preventDefault();
		addFieldFile($(this)[0]);
	});

});


function addFieldFile(formData){
	var siteUrl = $("#site_url").val();
	var urlToPost = siteUrl + "/program/ajax/programajax/addFieldFile";

	var data = new FormData(formData);

	$.ajax({
		url: urlToPost,
		type: 'post',
		data: data,
		async: false,
		cache: false,
		contentType: false,
		processData: false,
		success: function (data) {
			$("#status_field_file").html(data);
		}
	});
}
