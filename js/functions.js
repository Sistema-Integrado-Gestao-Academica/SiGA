
$(document).ready(function(){

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

	$("#start_period").datepicker($.datepicker.regional["pt-BR"], {
		dateFormat: "dd-mm-yy"
	});

	$("#end_period").datepicker($.datepicker.regional["pt-BR"], {
		dateFormat: "dd-mm-yy"
	});


	$("#installments_quantity").change(function(){
		checkInstallments();
	});

	$("#totalValue").change(function(){
		checkInstallments();
	});

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

});

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
		}
	);	
}

function checkInstallmentValue(){

	var totalValue = $("#totalValue").val();
	var quantityOfInstallments = $("#installments_quantity").val();
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
