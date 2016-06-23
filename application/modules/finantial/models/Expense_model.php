<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once(MODULESPATH."/finantial/constants/ExpenseNatureConstants.php");

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

	public function getAllExpenseTypes($onlyActives=FALSE) {
		
		$this->db->select('expense_type.*');
		$this->db->from('expense_type');
		if($onlyActives){
			$this->db->where('status !=', ExpenseNatureConstants::INACTIVE);
		}
		$expenseTypes = $this->db->get()->result_array();

		$expenseTypes = checkArray($expenseTypes);
		
		return $expenseTypes;
	}

	public function getExpenseType($value) {
		$objectToReturn = $this->db->get_where("expense_type", array('id' => $value))->row_array();
		$objectToReturn = checkArray($objectToReturn);
		return $objectToReturn;
	}

	public function updateExpenseType($expenseTypeId, $data){
		
		$this->db->where('id', $expenseTypeId);
		$updated = $this->db->update('expense_type', $data);
		return $updated;
	}

	public function createExpenseType($data){
		return $this->db->insert("expense_type", $data);
	}

	public function getLastExpenseType(){
		
		$query = $this->db->query("SELECT MAX(id) FROM expense_type");
		$row = $query->row_array();
	    $lastId = $row["MAX(id)"];

	    $expenseType = $this->getExpenseType($lastId);
		$expenseType = checkArray($expenseType);

		return $expenseType;
	}
}

/* End of file expense.php */
/* Location: ./application/models/expense.php */