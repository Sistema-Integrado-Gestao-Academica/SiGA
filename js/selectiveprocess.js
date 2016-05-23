$(document).ready(function(){

	$("#phase_2").ready(function(){

		getPhasesToSort();

		$(this).change(function(){
			getPhasesToSort();
		});
	});

	$("#phase_3").ready(function(){

		getPhasesToSort();
		
		$(this).change(function(){
			getPhasesToSort();
		});
	});
	
	$("#phase_4").ready(function(){

		getPhasesToSort();
		
		$(this).change(function(){
			getPhasesToSort();
		});
	});

	$("#open_selective_process_btn").click(function(){
		saveSelectiveProcess();
	});


	$("#selective_process_start_date").datepicker($.datepicker.regional["pt-BR"], {
		dateFormat: "dd-mm-yy"
	});

	$("#selective_process_end_date").datepicker($.datepicker.regional["pt-BR"], {
		dateFormat: "dd-mm-yy"
	});

});

function makeSortable(){
	$("#sortable").sortable();
	$("#sortable").sortable("option", "axis", "y");
	$("#sortable").sortable("option", "cursor", "move");
	$("#sortable").sortable("option", "containment", "parent");
}

function saveSelectiveProcess(){

	var course = $("#course").val();
	var studentType = $("#student_type").val();
	var noticeName = $("#selective_process_name").val();
	var startDate = $("#selective_process_start_date").val();
	var endDate = $("#selective_process_end_date").val();


	var preProject = $("#phase_2").val();
	var preProjectWeight = $("#phase_weight_2").val();
	
	var writtenTest = $("#phase_3").val();
	var writtenTestWeight = $("#phase_weight_2").val();
	
	var oralTest = $("#phase_4").val();
	var oralTestWeight = $("#phase_weight_4").val();
	
	var phasesOrder = $("#sortable").sortable("toArray");
	
	var siteUrl = $("#site_url").val();

	var urlToPost = siteUrl + "/program/ajax/selectiveprocessajax/newSelectionProcess";

	$.post(
		urlToPost,
		{
			course: course,
		    student_type: studentType,
		    selective_process_name: noticeName,
		    selective_process_start_date: startDate,
		    selective_process_end_date: endDate,
			phase_2: preProject,
			phase_weight_2: preProjectWeight,
		    phase_3: writtenTest,
		    phase_weight_3: writtenTestWeight,
		    phase_4: oralTest,
		    phase_weight_4: oralTestWeight,
		    phases_order: phasesOrder
		},
		function(data){
			$("#selection_process_saving_status").html(data);
		}
	);
}

function getPhasesToSort(){

	var preProject;
	var writtenTest;
	var oralTest;
	
	preProject = $("#phase_2").val();
	writtenTest = $("#phase_3").val();
	oralTest = $("#phase_4").val();

	var siteUrl = $("#site_url").val();

	var urlToPost = siteUrl + "/program/ajax/selectiveprocessajax/getPhasesToSort";

	$.post(
		urlToPost,
		{
			preProject: preProject,
			writtenTest: writtenTest,
			oralTest: oralTest	
		},
		function(data){
			$("#phases_list_to_order").html(data);
			makeSortable();
		}
	);
}
