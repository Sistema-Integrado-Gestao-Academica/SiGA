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
		$objectToReturn = $this->db->get_where("expense", array($attr => $value))->row_array();
		
		$objectToReturn =checkArray($objectToReturn);
		return $objectToReturn;
	}

	public function getAllExpenseTypes() {
		$objectToReturn = $this->db->get('expense_type')->result_array();
		$objectToReturn = checkArray($objectToReturn);
		return $objectToReturn;
	}

	public function getExpenseType($value) {
		$objectToReturn = $this->db->get_where("expense_type", array('id' => $value))->row_array();
		$objectToReturn = checkArray($objectToReturn);
		return $objectToReturn;
	}
}

/* End of file expense.php */
/* Location: ./application/models/expense.php */