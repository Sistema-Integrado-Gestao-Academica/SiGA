<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Payment_model extends CI_Model {

	public function getExpensePayments($expenseId){

		$payments = $this->db->get_where('payment', array('id_expense' => $expenseId))->result_array();

		$payments = checkArray($payments);

		return $payments;
	}
}