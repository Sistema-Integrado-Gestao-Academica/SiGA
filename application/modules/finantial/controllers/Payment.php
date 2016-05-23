<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(MODULESPATH."auth/constants/PermissionConstants.php");
require_once(MODULESPATH."finantial/domain/ServicePayment.php");

class Payment extends MX_Controller {

	public function expensePayments($expenseId, $budgetplanId){

		$this->load->model('finantial/payment_model');

		$payments = $this->payment_model->getExpensePayments($expenseId);

		$data = array(
			'payments' => $payments,
			'budgetplanId' => $budgetplanId,
			'expenseId' => $expenseId
		);


		loadTemplateSafelyByPermission(PermissionConstants::BUDGETPLAN_PERMISSION, 'finantial/payment/payments', $data);
	}

	public function newPayment($budgetplanId, $expenseId){

		$data = array(
			'budgetplanId' => $budgetplanId,
			'expenseId' => $expenseId
		);

		loadTemplateSafelyByPermission(PermissionConstants::BUDGETPLAN_PERMISSION, 'finantial/payment/new_payment', $data);
	}

	public function repayment($paymentId, $budgetplanId, $expenseId){

		$this->load->model('finantial/payment_model');

		$paymentData = $this->payment_model->getPayment($paymentId);

		$data = array(
			'budgetplanId' => $budgetplanId,
			'expenseId' => $expenseId,
			'payment' => $paymentData
		);

		loadTemplateSafelyByPermission(PermissionConstants::BUDGETPLAN_PERMISSION, "finantial/payment/repayment", $data);
	}

	public function employeePayment(){

		$budgetplanId = $this->input->post("budgetplanId");
		$expenseId = $this->input->post("expenseId");
		$employee = $this->input->post("employee");

		$data = array(
			'budgetplanId' => $budgetplanId,
			'expenseId' => $expenseId,
			'employee' => $employee
		);

		loadTemplateSafelyByPermission(PermissionConstants::BUDGETPLAN_PERMISSION, "finantial/payment/employee_payment", $data);
	}

	public function registerPayment(){

		$expense = $this->input->post("expenseId");
		$budgetplan = $this->input->post("budgetplanId");

		$userType = $this->input->post("userType");
		$legalSupport = $this->input->post("legalSupport");


		$resourseSource = $this->input->post("resourseSource");
		$costCenter = $this->input->post("costCenter");
		$dotationNote = (string) $this->input->post("dotationNote");

		$name = $this->input->post("name");
		$id = $this->input->post("id");
		$pisPasep = $this->input->post("pisPasep");
		$cpf = (string) $this->input->post("cpf");
		$enrollmentNumber = $this->input->post("enrollmentNumber");
		$arrivalInBrazil = $this->input->post("arrivalInBrazil");
		$phone = $this->input->post("phone");
		$email = $this->input->post("email");
		$address = $this->input->post("address");
		$projectDenomination = $this->input->post("projectDenomination");
		$bank = $this->input->post("bank");
		$agency = (string) $this->input->post("agency");
		$accountNumber = (string) $this->input->post("accountNumber");
		$installment_date_5 = $this->input->post("installment_date_5");

		$totalValue = $this->input->post("totalValue");
		$period = $this->input->post("start_period");
		$endPeriod = $this->input->post("end_period");
		$weekHours = $this->input->post("weekHours");
		$weeks = $this->input->post("weeks");
		$totalHours = $this->input->post("totalHours");
		$serviceDescription = $this->input->post("serviceDescription");

		$installment_date_1 = $this->input->post("installment_date_1");
		$installment_date_2 = $this->input->post("installment_date_2");
		$installment_date_3 = $this->input->post("installment_date_3");
		$installment_date_4 = $this->input->post("installment_date_4");
		$installment_date_5 = $this->input->post("installment_date_5");

		$installment_value_1 = $this->input->post("installment_value_1");
		$installment_value_2 = $this->input->post("installment_value_2");
		$installment_value_3 = $this->input->post("installment_value_3");
		$installment_value_4 = $this->input->post("installment_value_4");
		$installment_value_5 = $this->input->post("installment_value_5");

		$installment_hour_1 = $this->input->post("installment_hour_1");
		$installment_hour_2 = $this->input->post("installment_hour_2");
		$installment_hour_3 = $this->input->post("installment_hour_3");
		$installment_hour_4 = $this->input->post("installment_hour_4");
		$installment_hour_5 = $this->input->post("installment_hour_5");

		$installment1 = array(
			'date' => $installment_date_1,
			'value' => $installment_value_1,
			'hour' => $installment_hour_1
		);

		$installment2 = array(
			'date' => $installment_date_2,
			'value' => $installment_value_2,
			'hour' => $installment_hour_2
		);

		$installment3 = array(
			'date' => $installment_date_3,
			'value' => $installment_value_3,
			'hour' => $installment_hour_3
		);

		$installment4 = array(
			'date' => $installment_date_4,
			'value' => $installment_value_4,
			'hour' => $installment_hour_4
		);

		$installment5 = array(
			'date' => $installment_date_5,
			'value' => $installment_value_5,
			'hour' => $installment_hour_5
		);

		$payment = new ServicePayment(
			$userType, $legalSupport, $resourseSource, $costCenter, $dotationNote, $name,
			$id, $pisPasep, $cpf, $enrollmentNumber, $arrivalInBrazil, $phone, $email, $address, $projectDenomination, $bank,
			$agency, $accountNumber, $totalValue, $period, $endPeriod, $weekHours, $weeks, $totalHours, $serviceDescription,
			$installment1, $installment2, $installment3, $installment4, $installment5
		);

		$this->load->model("finantial/payment_model");
		$wasSaved = $this->payment_model->savePayment($expense, $payment);

		if($wasSaved){
			$status = "success";
			$message = "Pagamento registrado com sucesso.";
		}else{
			$status = "danger";
			$message = "Não foi possível registrar o pagamento informado.";
		}

		$session = getSession();
		$session->showFlashMessage($status, $message);
		redirect("expense_payments/{$expense}/{$budgetplan}");
	}

	public function registerRepayment(){

		$expense = $this->input->post("expenseId");
		$budgetplan = $this->input->post("budgetplanId");
		$paymentData = $this->input->post("paymentData");

		$userType = $paymentData["userType"];
		$legalSupport = $paymentData["legalSupport"];


		$resourseSource = $paymentData["resourseSource"];
		$costCenter = $paymentData["costCenter"];
		$dotationNote = $paymentData["dotationNote"];

		$name = $paymentData["name"];
		$id = $paymentData["id"];
		$pisPasep = $paymentData["pisPasep"];
		$cpf = $paymentData["cpf"];
		$enrollmentNumber = $paymentData["enrollmentNumber"];
		$arrivalInBrazil = $paymentData["arrivalInBrazil"];		
		$phone = $paymentData["phone"];
		$email = $paymentData["email"];
		$address = $paymentData["address"];
		$projectDenomination = $paymentData["projectDenomination"];
		$bank = $paymentData["bank"];
		$agency = $paymentData["agency"];
		$accountNumber = $paymentData["accountNumber"];
		$serviceDescription = $paymentData["serviceDescription"];

		$totalValue = $this->input->post("totalValue");
		$period = $this->input->post("start_period");
		$endPeriod = $this->input->post("end_period");
		$weekHours = $this->input->post("weekHours");
		$weeks = $this->input->post("weeks");
		$totalHours = $this->input->post("totalHours");

		$installment_date_1 = $this->input->post("installment_date_1");
		$installment_date_2 = $this->input->post("installment_date_2");
		$installment_date_3 = $this->input->post("installment_date_3");
		$installment_date_4 = $this->input->post("installment_date_4");
		$installment_date_5 = $this->input->post("installment_date_5");

		$installment_value_1 = $this->input->post("installment_value_1");
		$installment_value_2 = $this->input->post("installment_value_2");
		$installment_value_3 = $this->input->post("installment_value_3");
		$installment_value_4 = $this->input->post("installment_value_4");
		$installment_value_5 = $this->input->post("installment_value_5");

		$installment_hour_1 = $this->input->post("installment_hour_1");
		$installment_hour_2 = $this->input->post("installment_hour_2");
		$installment_hour_3 = $this->input->post("installment_hour_3");
		$installment_hour_4 = $this->input->post("installment_hour_4");
		$installment_hour_5 = $this->input->post("installment_hour_5");

		$installment1 = array(
			'date' => $installment_date_1,
			'value' => $installment_value_1,
			'hour' => $installment_hour_1
		);

		$installment2 = array(
			'date' => $installment_date_2,
			'value' => $installment_value_2,
			'hour' => $installment_hour_2
		);

		$installment3 = array(
			'date' => $installment_date_3,
			'value' => $installment_value_3,
			'hour' => $installment_hour_3
		);

		$installment4 = array(
			'date' => $installment_date_4,
			'value' => $installment_value_4,
			'hour' => $installment_hour_4
		);

		$installment5 = array(
			'date' => $installment_date_5,
			'value' => $installment_value_5,
			'hour' => $installment_hour_5
		);

		$payment = new ServicePayment(
			$userType, $legalSupport, $resourseSource, $costCenter, $dotationNote, $name,
			$id, $pisPasep, $cpf, $enrollmentNumber, $arrivalInBrazil, $phone, $email, $address, $projectDenomination, $bank,
			$agency, $accountNumber, $totalValue, $period, $endPeriod, $weekHours, $weeks, $totalHours, $serviceDescription,
			$installment1, $installment2, $installment3, $installment4, $installment5
		);

		$this->load->model("finantial/payment_model");
		$wasSaved = $this->payment_model->savePayment($expense, $payment);

		if($wasSaved){
			$status = "success";
			$message = "Pagamento registrado com sucesso.";
		}else{
			$status = "danger";
			$message = "Não foi possível registrar o pagamento informado.";
		}

		$session = getSession();
		$session->showFlashMessage($status, $message);
		redirect("expense_payments/{$expense}/{$budgetplan}");
	}

	public function generateSpreadsheet($paymentId){

		$this->load->model("finantial/payment_model");
		$paymentData = $this->payment_model->getPayment($paymentId);

		$userType = $paymentData["userType"];
		$legalSupport = $paymentData["legalSupport"];

		$resourseSource = $paymentData["resourseSource"];
		$costCenter = $paymentData["costCenter"];
		$dotationNote = $paymentData["dotationNote"];

		$name = $paymentData["name"];
		$id = $paymentData["id"];
		$pisPasep = $paymentData["pisPasep"];
		$cpf = $paymentData["cpf"];
		$enrollmentNumber = $paymentData["enrollmentNumber"];
		$arrivalInBrazil = $paymentData["arrivalInBrazil"];
		$phone = $paymentData["phone"];
		$email = $paymentData["email"];
		$address = $paymentData["address"];
		$projectDenomination = $paymentData["projectDenomination"];
		$bank = $paymentData["bank"];
		$agency = $paymentData["agency"];
		$accountNumber = $paymentData["accountNumber"];

		$totalValue = $paymentData["totalValue"];
		$period = $paymentData["period"];
		$endPeriod = $paymentData["end_period"];
		$weekHours = $paymentData["weekHours"];
		$weeks = $paymentData["weeks"];
		$totalHours = $paymentData["totalHours"];
		$serviceDescription = $paymentData["serviceDescription"];

		$installment_date_1 = $paymentData["installment_date_1"];
		$installment_date_2 = $paymentData["installment_date_2"];
		$installment_date_3 = $paymentData["installment_date_3"];
		$installment_date_4 = $paymentData["installment_date_4"];
		$installment_date_5 = $paymentData["installment_date_5"];

		$installment_value_1 = $paymentData["installment_value_1"];
		$installment_value_2 = $paymentData["installment_value_2"];
		$installment_value_3 = $paymentData["installment_value_3"];
		$installment_value_4 = $paymentData["installment_value_4"];
		$installment_value_5 = $paymentData["installment_value_5"];

		$installment_hour_1 = $paymentData["installment_hour_1"];
		$installment_hour_2 = $paymentData["installment_hour_2"];
		$installment_hour_3 = $paymentData["installment_hour_3"];
		$installment_hour_4 = $paymentData["installment_hour_4"];
		$installment_hour_5 = $paymentData["installment_hour_5"];

		$installment1 = array(
			'date' => $installment_date_1,
			'value' => $installment_value_1,
			'hour' => $installment_hour_1
		);

		$installment2 = array(
			'date' => $installment_date_2,
			'value' => $installment_value_2,
			'hour' => $installment_hour_2
		);

		$installment3 = array(
			'date' => $installment_date_3,
			'value' => $installment_value_3,
			'hour' => $installment_hour_3
		);

		$installment4 = array(
			'date' => $installment_date_4,
			'value' => $installment_value_4,
			'hour' => $installment_hour_4
		);

		$installment5 = array(
			'date' => $installment_date_5,
			'value' => $installment_value_5,
			'hour' => $installment_hour_5
		);

		$payment = new ServicePayment(
			$userType, $legalSupport, $resourseSource, $costCenter, $dotationNote, $name,
			$id, $pisPasep, $cpf, $enrollmentNumber, $arrivalInBrazil, $phone, $email, $address, $projectDenomination, $bank,
			$agency, $accountNumber, $totalValue, $period, $endPeriod, $weekHours, $weeks, $totalHours, $serviceDescription,
			$installment1, $installment2, $installment3, $installment4, $installment5
		);

		$payment->downloadSheet();
	}

}
