
$(document).ready(function(){

	// Back button
	$("#back_btn").click(function(){
		history.go(-1);
	});

	$("#employee_to_search").keypress(function(){
		searchEmployeeToPayment();
	});

	$("#discipline_search_btn").ready(function(){
		searchDisciplineClasses();
	});

	$("#discipline_search_btn").click(function(){
		searchDisciplineClasses();
	});

	$("#documentType").change(function(){
		checkDocumentType();
	});

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

	$("#alert").hover(function(){
		$("#alert").popover('show');
	},function(){
		$("#alert").popover('hide');
	});

	$("#arrivalInBrazil").datepicker($.datepicker.regional["pt-BR"], {
		dateFormat: "dd-mm-yy"
	});

	$("#start_period").datepicker($.datepicker.regional["pt-BR"], {
		dateFormat: "dd-mm-yy"
	});

	$("#end_period").datepicker($.datepicker.regional["pt-BR"], {
		dateFormat: "dd-mm-yy"
	});

	$("#selective_process_start_date").datepicker($.datepicker.regional["pt-BR"], {
		dateFormat: "dd-mm-yy"
	});

	$("#selective_process_end_date").datepicker($.datepicker.regional["pt-BR"], {
		dateFormat: "dd-mm-yy"
	});

	$("#totalValue").change(function(){
		checkInstallments();
	});

	$("#installments_quantity").change(function(){
		checkInstallments();
	});

});

function searchEmployeeToPayment(){

	var employeeName = $("#employee_to_search").val();
	var budgetplanId = $("#budgetplanId").val();
	var expenseId = $("#expenseId").val();

	var siteUrl = $("#site_url").val();

	var urlToPost = siteUrl + "/ajax/paymentajax/newStaffPaymentForm";

	$.post(
		urlToPost,
		{
			employeeName: employeeName,
			budgetplanId: budgetplanId,
			expenseId: expenseId
		},
		function(data){
			$("#employee_search_result").html(data);
		}
	);
}

function searchDisciplineClasses(){

	var disciplineName = $("#discipline_name_search").val();
	var courseId = $("#courseId").val();
	var userId = $("#userId").val();
	var siteUrl = $("#site_url").val();

	var urlToPost = siteUrl + "/temporaryrequest/searchDisciplinesToRequest";

	$.post(
		urlToPost,
		{
			disciplineName: disciplineName,
			courseId: courseId,
			userId: userId
		},
		function(data){
			$("#discipline_search_result").html(data);
		}
	);
}

function checkInstallments(){

	var totalValue = $("#totalValue").val();
	var quantityOfInstallments = $("#installments_quantity").val();

	var siteUrl = $("#site_url").val();
	var urlToPost = siteUrl + "/payment/checkInstallmentQuantity";

	$.post(
		urlToPost,
		{
			totalValue: totalValue,
			installments: quantityOfInstallments
		},
		function(data){
			$("#installments_data").html(data);

			$("#installment_date_1").datepicker($.datepicker.regional["pt-BR"], {
				dateFormat: "dd-mm-yy"
			});

			$("#installment_date_2").datepicker($.datepicker.regional["pt-BR"], {
				dateFormat: "dd-mm-yy"
			});

			$("#installment_date_3").datepicker($.datepicker.regional["pt-BR"], {
				dateFormat: "dd-mm-yy"
			});


			$("#installment_date_4").datepicker($.datepicker.regional["pt-BR"], {
				dateFormat: "dd-mm-yy"
			});

			$("#installment_date_5").datepicker($.datepicker.regional["pt-BR"], {
				dateFormat: "dd-mm-yy"
			});

			checkInstallmentValue();

			$("#installment_value_1").change(function(){
				checkInstallmentValue();
			});

			$("#installment_value_2").change(function(){
				checkInstallmentValue();
			});

			$("#installment_value_3").change(function(){
				checkInstallmentValue();
			});

			$("#installment_value_4").change(function(){
				checkInstallmentValue();
			});

			$("#installment_value_5").change(function(){
				checkInstallmentValue();
			});
		}
	);
}

function checkInstallmentValue(){

	var totalValue = $("#totalValue").val();

	var installment_value_1 = $("#installment_value_1").val();
	var installment_value_2 = $("#installment_value_2").val();
	var installment_value_3 = $("#installment_value_3").val();
	var installment_value_4 = $("#installment_value_4").val();
	var installment_value_5 = $("#installment_value_5").val();

	var siteUrl = $("#site_url").val();
	var urlToPost = siteUrl + "/payment/checkInstallmentValues";

	$.post(
		urlToPost,
		{
			totalValue: totalValue,
			installment1: installment_value_1,
			installment2: installment_value_2,
			installment3: installment_value_3,
			installment4: installment_value_4,
			installment5: installment_value_5
		},
		function(data){
			$("#check_installment_result").html(data);
		}
	);
}

function checkDocumentType(){

	var currentType = $("#documentType").val();
	var siteUrl = $("#site_url").val();

	var urlToPost = siteUrl + "/documentrequest/checkDocumentType";

	$.post(
		urlToPost,
		{documentType: currentType},
		function(data){
			$("#document_request_data").html(data);
		}
	);
}

// Student functions
function searchForStudent(){
	var studentName = $("#student_name").val();
	var siteUrl = $("#site_url").val();

	var urlToPost = siteUrl + "/usuario/searchForStudent";
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
