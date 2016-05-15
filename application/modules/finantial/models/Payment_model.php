<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(MODULESPATH."finantial/domain/ServicePayment.php");

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
			'end_period' => $servicePayment->endPeriod(),
			'weekHours' => $servicePayment->weekHours(),
			'weeks' => $servicePayment->weeks(),
			'totalHours' => $servicePayment->totalHours(),
			'serviceDescription' => $servicePayment->serviceDescription(),

			'installment_date_1' => $servicePayment->installment1()['date'],
			'installment_date_2' => $servicePayment->installment2()['date'],
			'installment_date_3' => $servicePayment->installment3()['date'],
			'installment_date_4' => $servicePayment->installment4()['date'],
			'installment_date_5' => $servicePayment->installment5()['date'],

			'installment_value_1' => $servicePayment->installment1()['value'],
			'installment_value_2' => $servicePayment->installment2()['value'],
			'installment_value_3' => $servicePayment->installment3()['value'],
			'installment_value_4' => $servicePayment->installment4()['value'],
			'installment_value_5' => $servicePayment->installment5()['value'],

			'installment_hour_1' => $servicePayment->installment1()['hour'],
			'installment_hour_2' => $servicePayment->installment2()['hour'],
			'installment_hour_3' => $servicePayment->installment3()['hour'],
			'installment_hour_4' => $servicePayment->installment4()['hour'],
			'installment_hour_5' => $servicePayment->installment5()['hour']
		);

		$wasSaved = $this->db->insert(self::TABLE_NAME, $payment);

		return $wasSaved;
	}
}