<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH."/constants/PermissionConstants.php");
require_once(APPPATH."/data_types/Spreadsheet.php");

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

	public function newPropose(){

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

		$spreadsheet = new Spreadsheet($userType, $legalSupport, $resourseSource, $costCenter, $dotationNote, $name,
			$id, $pisPasep, $cpf, $enrollmentNumber, $arrivalInBrazil, $phone, $address, $projectDenomination, $bank,
			$agency, $accountNumber, $totalValue, $period, $weekHours, $weeks, $totalHours, $serviceDescription);

		$spreadsheet->generateSheet();

	}

}
