<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(MODULESPATH."/finantial/constants/ExpenseNatureConstants.php");
require_once(MODULESPATH."/finantial/domain/ExpenseDetail.php");

class Expense_model extends CI_Model {

	public function save($object) {
		return $this->db->insert("expense", $object);
	}

	public function delete($id) {
		$this->db->where('id', $id);
		return $this->db->delete('expense');
	}

	public function getId($attr, $value) {
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

	public function createExpenseDetail($data){
		return $this->db->insert("expense_detail", $data);
	}

	public function updateExpenseDetail($expenseDetailId, $data){
		$this->db->where('id', $expenseDetailId);
		return $this->db->update("expense_detail", $data);
	}

	public function getExpenseDetail($expenseDetailId){
		
		$this->db->select('expense_detail.*');
		$this->db->from('expense_detail');
		$this->db->where('expense_detail.id', $expenseDetailId);
		$foundExpenses = $this->db->get()->result_array();

		$foundExpenses = checkArray($foundExpenses);

		foreach ($foundExpenses as $foundExpense) {
				
			$expense = $this->createObjectExpenseDetail($foundExpense);

		}

		return $expense;		
	}

	public function getExpenseIdOfAExpenseDetail($expenseDetailId){

		$this->db->select('expense_detail.expense_id');
		$this->db->from('expense_detail');
		$this->db->where('expense_detail.id', $expenseDetailId);
		$foundExpense = $this->db->get()->result_array();

		$foundExpense = checkArray($foundExpense);

		return $foundExpense[0]['expense_id'];
	}

	public function getAllExpensesFromAExpense($expenseId){

		$this->db->select('expense_detail.*');
		$this->db->from('expense_detail');
		$this->db->where('expense_detail.expense_id', $expenseId);
		$foundExpenses = $this->db->get()->result_array();

		$foundExpenses = checkArray($foundExpenses);
		
		$expenses = array();
		if($foundExpenses !== FALSE){
			
			foreach ($foundExpenses as $foundExpense) {
				
				$expense = $this->createObjectExpenseDetail($foundExpense);
				array_push($expenses, $expense);

			}
		}

		return $expenses;
	}

	private function createObjectExpenseDetail($expenseArray){
		
		$emissionDate = $expenseArray['emission_date'];
		$emissionDate = ExpenseDetail::formatDateToBR($emissionDate);
		if($emissionDate == "00/00/0000"){
			$emissionDate = "";
		}

		$expense = new ExpenseDetail($expenseArray['note'], $emissionDate, $expenseArray['sei_process'],
								$expenseArray['value'], $expenseArray['description'], $expenseArray['id']);

		return $expense;
	}
}

/* End of file expense.php */
/* Location: ./application/models/expense.php */