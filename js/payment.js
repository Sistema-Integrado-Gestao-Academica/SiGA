$(document).ready(function(){

	// Back button
	$("#back_btn").click(function(){
		history.go(-1);
	});

	$("#employee_to_search").keypress(function(){
		searchEmployeeToPayment();
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
	
	$("#totalValue").change(function(){
		checkInstallments();
	});

	$("#installments_quantity").change(function(){
		checkInstallments();
	});
});

function checkInstallments(){

	var totalValue = $("#totalValue").val();
	var quantityOfInstallments = $("#installments_quantity").val();

	var siteUrl = $("#site_url").val();
	var urlToPost = siteUrl + "/finantial/ajax/paymentajax/checkInstallmentQuantity";

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
	var urlToPost = siteUrl + "/finantial/ajax/paymentajax/checkInstallmentValues";

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


function searchEmployeeToPayment(){

	var employeeName = $("#employee_to_search").val();
	var budgetplanId = $("#budgetplanId").val();
	var expenseId = $("#expenseId").val();

	var siteUrl = $("#site_url").val();

	var urlToPost = siteUrl + "/finantial/ajax/paymentajax/newStaffPaymentForm";

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