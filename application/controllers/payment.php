<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH."/constants/PermissionConstants.php");
require_once(APPPATH."/data_types/ServicePayment.php");

class Payment extends CI_Controller {

	public function expensePayments($expenseId, $budgetplanId){
		
		$this->load->model('payment_model');
		
		$payments = $this->payment_model->getExpensePayments($expenseId);

		$data = array(
			'payments' => $payments,
			'budgetplanId' => $budgetplanId,
			'expenseId' => $expenseId
		);

		loadTemplateSafelyByPermission(PermissionConstants::BUDGETPLAN_PERMISSION, 'payment/payments', $data);
	}

	public function newPayment($budgetplanId, $expenseId){

		$data = array(
			'budgetplanId' => $budgetplanId,
			'expenseId' => $expenseId
		);

		loadTemplateSafelyByPermission(PermissionConstants::BUDGETPLAN_PERMISSION, 'payment/new_payment', $data);
	}

	public function registerPayment(){

		$expense = $this->input->post("expenseId");
		$budgetplan = $this->input->post("budgetplanId");

		$userType = $this->input->post("userType");
		$legalSupport = $this->input->post("legalSupport");

		$resourseSource = $this->input->post("resourseSource");
		$costCenter = $this->input->post("costCenter");
		$dotationNote = $this->input->post("dotationNote");
		
		$name = $this->input->post("name");
		$id = $this->input->post("id");
		$pisPasep = $this->input->post("pisPasep");
		$cpf = $this->input->post("cpf");
		$enrollmentNumber = $this->input->post("enrollmentNumber");
		$arrivalInBrazil = $this->input->post("arrivalInBrazil");
		$phone = $this->input->post("phone");
		$email = $this->input->post("email");
		$address = $this->input->post("address");
		$projectDenomination = $this->input->post("projectDenomination");
		$bank = $this->input->post("bank");
		$agency = $this->input->post("agency");
		$accountNumber = $this->input->post("accountNumber");

		$totalValue = $this->input->post("totalValue");
		$period = $this->input->post("period");
		$weekHours = $this->input->post("weekHours");
		$weeks = $this->input->post("weeks");
		$totalHours = $this->input->post("totalHours");
		$serviceDescription = $this->input->post("serviceDescription");

		$payment = new ServicePayment(
			$userType, $legalSupport, $resourseSource, $costCenter, $dotationNote, $name,
			$id, $pisPasep, $cpf, $enrollmentNumber, $arrivalInBrazil, $phone, $email, $address, $projectDenomination, $bank,
			$agency, $accountNumber, $totalValue, $period, $weekHours, $weeks, $totalHours, $serviceDescription
		);

		$this->load->model("payment_model");
		$wasSaved = $this->payment_model->savePayment($expense, $payment);

		if($wasSaved){
			$status = "success";
			$message = "Pagamento registrado com sucesso.";
		}else{
			$status = "danger";
			$message = "Não foi possível registrar o pagamento informado.";
		}

		$this->session->set_flashdata($status, $message);
		redirect("payment/expensePayments/{$expense}/{$budgetplan}");
	}

	public function generateSpreadsheet($paymentId){

		$this->load->model("payment_model");
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
		$weekHours = $paymentData["weekHours"];
		$weeks = $paymentData["weeks"];
		$totalHours = $paymentData["totalHours"];
		$serviceDescription = $paymentData["serviceDescription"];

		$payment = new ServicePayment(
			$userType, $legalSupport, $resourseSource, $costCenter, $dotationNote, $name,
			$id, $pisPasep, $cpf, $enrollmentNumber, $arrivalInBrazil, $phone, $email, $address, $projectDenomination, $bank,
			$agency, $accountNumber, $totalValue, $period, $weekHours, $weeks, $totalHours, $serviceDescription
		);

		$payment->downloadSheet();
	}
}
