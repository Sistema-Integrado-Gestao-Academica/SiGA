
$(document).ready(function(){

	$(document).on('submit', '#add_field_file_form', function(e){
    	e.preventDefault();
		addFieldFile($(this)[0]);
	});

	$('#add_info_btn').click(function(e) {
    	e.preventDefault();
		addInfo();
	});

	(function($) {

	  hide_show = function(data) {
	  	changeExtraInfoStatus(data);
	  };
	})(jQuery);
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

function changeExtraInfoStatus(infoId){
	var siteUrl = $("#site_url").val();
	var urlToPost = siteUrl + "/program/ajax/programajax/changeExtraInfoStatus";

	var data = {
		infoId: infoId
	}
	$.post(
		urlToPost,
		data,
		function(data){
			var values = JSON.parse(data);
			$("#label_" + infoId).html(values.label);
			$("#button_" + infoId).html(values.button);
		}
	);
}