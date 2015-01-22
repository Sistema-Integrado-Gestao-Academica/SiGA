<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Expense_model extends CI_Model {

	public function save($object) {
		return $this->db->insert("expense", $object);
	}

	public function delete($id) {
		$this->db->where('id', $id);
		return $this->db->delete('expense');
	}

	public function get($attr, $value) {
		return $this->db->get_where("expense", array($attr => $value))->row_array();
	}

	public function getAllExpenseTypes() {
		return $this->db->get('expense_type')->result_array();
	}

	public function getExpenseType($value) {
		return $this->db->get_where("expense_type", array('id' => $value))->row_array();
	}
}

/* End of file expense.php */
/* Location: ./application/models/expense.php */