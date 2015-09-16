<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH."/data_types/ServicePayment.php");

class Payment_model extends CI_Model {

	// Table handled by the model
	const TABLE_NAME = "payment";

	public function getExpensePayments($expenseId){

		$payments = $this->db->get_where('payment', array('id_expense' => $expenseId))->result_array();

		$payments = checkArray($payments);

		return $payments;
	}

	public function getPayment($paymentId){

		$payment = $this->db->get_where('payment', array('id_payment' => $paymentId))->row_array();

		$payment = checkArray($payment);

		return $payment;
	}

	/**
	 * Save a payment on the database
	 * @param $expense - The expense id that generated the payment
	 * @param $servicePayment - a ServicePayment object with the payment data
	 */
	public function savePayment($expense, $servicePayment){

		$payment = array(
			'id_expense' => $expense,
			'userType' => $servicePayment->userType(),
			'legalSupport' => $servicePayment->legalSupport(),

			'resourseSource' => $servicePayment->resourseSource(),
			'costCenter' => $servicePayment->costCenter(),
			'dotationNote' => $servicePayment->dotationNote(),
			
			'name' => $servicePayment->name(),
			'id' => $servicePayment->id(),
			'pisPasep' => $servicePayment->pisPasep(),
			'cpf' => $servicePayment->cpf(),
			'enrollmentNumber' => $servicePayment->enrollmentNumber(),
			'arrivalInBrazil' => $servicePayment->arrivalInBrazil(),
			'phone' => $servicePayment->phone(),
			'email' => $servicePayment->email(),
			'address' => $servicePayment->address(),
			'projectDenomination' => $servicePayment->projectDenomination(),
			'bank' => $servicePayment->bank(),
			'agency' => $servicePayment->agency(),
			'accountNumber' => $servicePayment->accountNumber(),

			'totalValue' => $servicePayment->totalValue(),
			'period' => $servicePayment->period(),
			'weekHours' => $servicePayment->weekHours(),
			'weeks' => $servicePayment->weeks(),
			'totalHours' => $servicePayment->totalHours(),
			'serviceDescription' => $servicePayment->serviceDescription()
		);

		$wasSaved = $this->db->insert(self::TABLE_NAME, $payment);

		return $wasSaved;
	}
}