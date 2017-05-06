$(document).ready(function(){


	$("#phase_select_2").ready(function(){
		var toSort = true;

		showOrHide("#phase_select_2", "#phase_2_fields");

		if($('#phase_select_2').attr('disabled')){
			toSort = false;
		}

		getPhasesToSort(toSort);

		$(this).change(function(){
			getPhasesToSort(toSort);
			showOrHide("#phase_select_2", "#phase_2_fields");
		});
	});

	$("#phase_select_3").ready(function(){
		var toSort = true;

		showOrHide("#phase_select_3", "#phase_3_fields");

		if($('#phase_select_3').attr('disabled')){
			toSort = false;
		}
		getPhasesToSort(toSort);

		$(this).change(function(){
			getPhasesToSort(toSort);
			showOrHide("#phase_select_3", "#phase_3_fields");
		});
	});

	$("#phase_select_4").ready(function(){
		var toSort = true;

		showOrHide("#phase_select_4", "#phase_4_fields");

		if($('#phase_select_4').attr('disabled')){
			toSort = false;
		}
		getPhasesToSort(toSort);

		$(this).change(function(){
			getPhasesToSort(toSort);
			showOrHide("#phase_select_4", "#phase_4_fields");
		});
	});


	$("#open_selective_process_btn").click(function(){
		saveSelectiveProcess("newSelectionProcess");
	});

	$("#edit_selective_process_btn").click(function(e){
	    e.preventDefault();
		saveSelectiveProcess("updateSelectionProcess");
	});

	$(document).on('focus',"#start_date", function(){
	    $(this).datepicker($.datepicker.regional["pt-BR"], {
		dateFormat: "dd-mm-yy"});
	});

	$(document).on('focus',"#end_date", function(){
	    $(this).datepicker($.datepicker.regional["pt-BR"], {
		dateFormat: "dd-mm-yy"});
	});

	$(document).on('focus',"#divulgation_start_date", function(){
	    $(this).datepicker($.datepicker.regional["pt-BR"], {
		dateFormat: "dd-mm-yy"});
	});

	$(document).on('click', '#define_date_phase_1', function(e){
	    e.preventDefault();
		definePhaseDate(1);
	});

	$(document).on('click', '#define_date_phase_2', function(e){
    	e.preventDefault();
		definePhaseDate(2);
	});

	$(document).on('click', '#define_date_phase_3', function(e){
    	e.preventDefault();
		definePhaseDate(3);
	});

	$(document).on('click', '#define_date_phase_4', function(e){
    	e.preventDefault();
		definePhaseDate(4);
	});

	$(document).on('click', '#define_subscription_date', function(e){
    	e.preventDefault();
		defineSubscriptionDate();
	});

	$(document).on('focus',"#phase_1_start_date", function(){
	    $(this).datepicker($.datepicker.regional["pt-BR"], {
		dateFormat: "dd-mm-yy"});
	});

	$(document).on('focus',"#phase_1_end_date", function(){
		$(this).datepicker($.datepicker.regional["pt-BR"], {
			dateFormat: "dd-mm-yy"});
	});

	$(document).on('focus',"#phase_2_start_date", function(){
	    $(this).datepicker($.datepicker.regional["pt-BR"], {
		dateFormat: "dd-mm-yy"});
	});

	$(document).on('focus',"#phase_2_end_date", function(){
		$(this).datepicker($.datepicker.regional["pt-BR"], {
			dateFormat: "dd-mm-yy"});
	});

	$(document).on('focus',"#phase_3_start_date", function(){
	    $(this).datepicker($.datepicker.regional["pt-BR"], {
		dateFormat: "dd-mm-yy"});
	});

	$(document).on('focus',"#phase_3_end_date", function(){
		$(this).datepicker($.datepicker.regional["pt-BR"], {
			dateFormat: "dd-mm-yy"});
	});

	$(document).on('focus',"#phase_4_start_date", function(){
	    $(this).datepicker($.datepicker.regional["pt-BR"], {
		dateFormat: "dd-mm-yy"});
	});

	$(document).on('focus',"#phase_4_end_date", function(){
		$(this).datepicker($.datepicker.regional["pt-BR"], {
			dateFormat: "dd-mm-yy"});
	});

	$("#save_research_line").click(function(){
		saveResearchLine();
	});

	(function($) {

	  addTimelineItem = function(processId) {
	  	// Zero represents FALSE
		addFormToAddDivulgation(processId);
	  };
	})(jQuery);

	(function($) {

	  saveDefinedDates = function(processId, phasesIds) {
		if(!document.getElementById('dates_defined')){
			setDatesDefined(processId, phasesIds);
		}
		else{
			openTab("#define_teachers_link");
		}

		return false;
	  };
	})(jQuery);

	(function($) {

	  saveSelectedTeachers = function(processId) {
		setTeachersSelected(processId);

		return false;
	  };
	})(jQuery);

	$("#back_to_define_dates").click(function(e){
    	e.preventDefault();
		openTab('#dates_link');
	});

	$("#back_to_define_teachers").click(function(e){
    	e.preventDefault();
		openTab('#define_teachers_link');
	});

	$("#back_to_edit_process").click(function(e){
    	e.preventDefault();
		openTab('#edit_process_link');
	});

	$("#phase_type_2").ready(function(){

		showOrHide("#phase_type_2", "#phase_grade_2_div");

		$(this).change(function(){
			showOrHide("#phase_type_2", "#phase_grade_2_div");
		});
	});

	$("#phase_type_3").ready(function(){
		showOrHide("#phase_type_3", "#phase_grade_3_div");

		$(this).change(function(){
			showOrHide("#phase_type_3", "#phase_grade_3_div");
		});

	});

	$("#phase_type_4").ready(function(){
		showOrHide("#phase_type_4", "#phase_grade_4_div");

		$(this).change(function(){
			showOrHide("#phase_type_4", "#phase_grade_4_div");
		});

	});
});

function showOrHide(typeSelectElementId, divId){

	if($(typeSelectElementId).val() == 1){
		$(divId).show();
	}
	else{
		$(divId).hide();
	}

}

function makeSortable(){
	$("#sortable").sortable();
	$("#sortable").sortable("option", "axis", "y");
	$("#sortable").sortable("option", "cursor", "move");
	$("#sortable").sortable("option", "containment", "parent");
}

function saveSelectiveProcess(saveMethod){

	var course = $("#course").val();
	var studentType = $("#student_type").val();
	var noticeName = $("#selective_process_name").val();
	var vacancies = $("#total_vacancies").val();
	var passing_score = $("#passing_score").val();

	var preProject = $("#phase_select_2").val();
	var preProjectWeight = $("#phase_weight_2").val();
	var preProjectGrade = $("#phase_grade_2").val();
	var preProjectType = $("#phase_type_2").val();

	var writtenTest = $("#phase_select_3").val();
	var writtenTestWeight = $("#phase_weight_3").val();
	var writtenTestGrade = $("#phase_grade_3").val();
	var writtenTestType = $("#phase_type_3").val();

	var oralTest = $("#phase_select_4").val();
	var oralTestWeight = $("#phase_weight_4").val();
	var oralTestGrade = $("#phase_grade_4").val();
	var oralTestType = $("#phase_type_4").val();

	var phasesOrder = $("#sortable").sortable("toArray");

	var siteUrl = $("#site_url").val();

	var urlToPost = siteUrl + "/program/ajax/selectiveprocessajax/"+ saveMethod;

	var data = {
		course: course,
	    student_type: studentType,
	    selective_process_name: noticeName,
	    total_vacancies: vacancies,
	    passing_score: passing_score,
		phase_2: preProject,
		phase_weight_2: preProjectWeight,
		phase_grade_2: preProjectGrade,
		phase_type_2: preProjectType,
	    phase_3: writtenTest,
	    phase_weight_3: writtenTestWeight,
		phase_grade_3: writtenTestGrade,
		phase_type_3: writtenTestType,
	    phase_4: oralTest,
	    phase_weight_4: oralTestWeight,
		phase_grade_4: oralTestGrade,
		phase_type_4: oralTestType,
	    phases_order: phasesOrder
	}
	if(document.getElementById("processId")){
		var processId = $("#processId").val();
	    data['processId'] = processId;
	}

	$.post(
		urlToPost,
		data,
		function(data){
			var response = JSON.parse(data);
			if(response.status){
				if(saveMethod === "newSelectionProcess"){
					window.setTimeout(function () {
			        	location.href = siteUrl + "/selection_process/config/" + response.processId;
					}, 1000);
				}
				else{
					if(response.phasesChanged){
						var phases = JSON.parse(response.phases);
						organizePhasesOnDefineDate(siteUrl, processId, phases);
					}
					openTab("#dates_link");
				}
			}
			else{
				$("#selection_process_error_status").html("<p class='alert alert-danger'>" + response.message + "</p>");
				scrollTo(0,0);
			}
		}
	);
}

function getPhasesToSort(toSort){

	var preProject;
	var writtenTest;
	var oralTest;

	preProject = $("#phase_select_2").val();
	writtenTest = $("#phase_select_3").val();
	oralTest = $("#phase_select_4").val();
	var siteUrl = $("#site_url").val();

	data = {
		preProject: preProject,
		writtenTest: writtenTest,
		oralTest: oralTest
	}

	if(!document.getElementById("processId")){

		var urlToPost = siteUrl + "/program/ajax/selectiveprocessajax/getPhasesToSort";

		$.post(
			urlToPost,
			data,
			function(data){
				$("#phases_list_to_order").html(data);
				if(data.includes("danger")){
					$("#open_selective_process_btn").addClass('disabled');
				}
				else{
					$("#open_selective_process_btn").removeClass('disabled');
				}
				makeSortable();
			}
		);
	}
	else{
		var processId = $("#processId").val();
	    var urlToPost = siteUrl + "/program/ajax/selectiveprocessajax/showPhasesInOrder";
	    data['processId'] = processId;
		$.post(
			urlToPost,
			data,
			function(data){
				$("#phases_list_to_order_in_edition").html(data);
				if(data.includes("danger")){
					$("#edit_selective_process_btn").addClass('disabled');
				}
				else{
					$("#edit_selective_process_btn").removeClass('disabled');
				}
				makeSortable();
				if(!toSort){
					$("#sortable").sortable("disable");
				}
			}
		);
	}

}

function definePhaseDate(phaseId){
	var siteUrl = $("#site_url").val();
	var idFieldStartDate = "#phase_" + phaseId + "_start_date"
	var startDate = $(idFieldStartDate).val();
	var idFieldEndDate = "#phase_" + phaseId + "_end_date"
	var endDate = $(idFieldEndDate).val();
	var urlToPost = siteUrl + "/program/ajax/selectiveprocessajax/definePhaseDate/"+ phaseId;
	var process_id = $("#process_id").val();

	var data = {
		startDate: startDate,
		endDate: endDate,
		process_id: process_id
	}
	$.post(
		urlToPost,
		data,
		function(data){
			id = "#phase_" + phaseId;
			$(id).html(data);
		}
	);
}

function defineSubscriptionDate(){
	var siteUrl = $("#site_url").val();
	var start_date = $("#start_date").val();
	var end_date = $("#end_date").val();
	var process_id = $("#process_id").val();
	var urlToPost = siteUrl + "/program/ajax/selectiveprocessajax/defineSubscriptionDate/" + process_id;
	var course_id = $("#course_id").val();

	var data = {
		start_date: start_date,
		end_date: end_date,
		course_id: course_id
	}
	$.post(
		urlToPost,
		data,
		function(data){
			$("#subscription").html(data);
		}
	);
}

function addFormToAddDivulgation(processId){
	var siteUrl = $("#site_url").val();
	var urlToPost = siteUrl + "/program/ajax/selectiveprocessdivulgationajax/addFormToAddDivulgation/" + processId;

	$.get(
		urlToPost,
		function(data){
			$("#new_divulgation").html(data);
			var style = {
				buttonText: "Procurar arquivo",
				buttonName: "btn btn-primary",
				iconName: "fa fa-file",
				placeholder: "Nenhum arquivo selecionado"};

			$(':file').filestyle(style);
		}
	);
}

function saveResearchLine(){
	var research_line = $("#research_line").val();
	var course_id = $("#research_course").val();

	var siteUrl = $("#site_url").val();
	var urlToPost = siteUrl + "/program/ajax/courseajax/saveResearchLine";

	var data = {
		research_line: research_line,
		course_id: course_id
	}

	$.post(
		urlToPost,
		data,
		function(data){
			$("#result").html(data);
			if(data.includes("success")){
				$("#callout_research_line").remove();
				$("#research_lines").append("<li>"+ research_line + "</li>");
			}
		}
	);
}

function setDatesDefined(processId, phasesIds){

	var datesWereDefined = checkIfDatesWereDefined(phasesIds);

	var siteUrl = $("#site_url").val();
	var urlToPost = siteUrl + "/program/ajax/selectiveprocessajax/setDatesDefined/" + processId;

	var data = {
		dates_defined: datesWereDefined
	};

	$.post(
		urlToPost,
		data,
		function(response){
			if(datesWereDefined){
				openTab('#define_teachers_link');
				$("#warning_message").hide();
				$("#save_dates_btn").attr('id', 'dates_defined');
			}
			else{
				openTab('#define_teachers_link');
				$("#warning_message").show();
				$("#warning_message").append("<br><i class='fa fa-warning'></i>Você não definiu a data de todas as fases.");
			}
		}
	);
}


function setTeachersSelected(processId){

	var hasTeachers = $("#teachers_added_to_process_table").find('tbody:last > tr').length != 0;

	var data = {
		teachers_selected: hasTeachers
	};

	var siteUrl = $("#site_url").val();
	var urlToPost = siteUrl + "/program/ajax/selectiveprocessajax/setTeachersSelected/" + processId;

	$.post(
		urlToPost,
		data,
		function(data){
			if(hasTeachers){
				openTab('#config_subscription_link');
				$("#warning_message").hide();
			}
			else{
				openTab('#config_subscription_link');
				$("#warning_message").show();
				$("#warning_message").append("<br><i class='fa fa-warning'></i>Você não definiu nenhum professor para fazer parte da comissão de seleção.");
			}
		}
	);

}

function openTab(tabId){
	$(tabId).click();
	scrollTo(0,0);
}

function checkIfDatesWereDefined(phasesIds){

	var subscriptionStartDate = $("#start_date").val();
	var subscriptionEndDate = $("#end_date").val();
	var datesWereDefined = true;

	if(subscriptionStartDate != "" || subscriptionEndDate != ""){
		var ids = phasesIds.split(';');
		for(var i=0; i < ids.length; i++){
			var startDateId = "#phase_" + ids[i] + "_start_date";
			var endDateId = "#phase_" + ids[i] + "_end_date";
			var phaseStartDate = $(startDateId).val();
			var phaseEndDate = $(endDateId).val();
			if(phaseStartDate === "" || phaseEndDate === ""){
				datesWereDefined = false;
				break;
			}
		}
	}
	else{
		datesWereDefined = false;
	}

	return datesWereDefined;
}

function organizePhasesOnDefineDate(siteUrl, processId, phases){

	var urlToPost = siteUrl + "/program/ajax/selectiveprocessajax/addDefineDatesTimeline/" + processId;
	var data = {
		phases: phases,
	}

	$.post(
		urlToPost,
		data,
		function(data){
			$("#define_dates_timeline").html(data);
		}
	);
}



