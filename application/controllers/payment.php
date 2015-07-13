<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Payment extends CI_Controller {

	public function expensePayments($expenseId, $budgetplanId){
		
		$this->load->model('payment_model');
		
		$payments = $this->payment_model->getExpensePayments($expenseId);

		$data = array(
			'payments' => $payments,
			'budgetplanId' => $budgetplanId
		);

		loadTemplateSafelyByGroup('secretario', 'payment/new_payment', $data);
	}

}
