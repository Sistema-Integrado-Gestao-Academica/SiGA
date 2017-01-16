
$(document).ready(function(){

	$(document).on('submit', '#add_field_file_form', function(e){
    	e.preventDefault();
		addFieldFile($(this)[0]);
	});

	$('#add_info_btn').click(function(e) {
    	e.preventDefault();
		addInfo();
	});

	$(document).on('click', '#hide_btn', function(e){
    	e.preventDefault();
		changeExtraInfoStatus('hide');
	});

	$(document).on('click', '#show_btn', function(e){
    	e.preventDefault();
		changeExtraInfoStatus('show');
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
			$("#add_result").html(data);
		}
	});
}


function addInfo(){
	var siteUrl = $("#site_url").val();
	var title = $("#title").val();
	var details = $("#details").val();
	var urlToPost = siteUrl + "/program/ajax/programajax/addInformationOnPortal";
	var program_id = $("#program_id").val();

	var data = {
		details: details,
		title: title,
		program_id: program_id
	}
	$.post(
		urlToPost,
		data,
		function(data){
			$("#add_result").html(data);
		}
	);
}

function changeExtraInfoStatus(){
	
}