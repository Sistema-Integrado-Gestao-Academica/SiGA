<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Budgetplan_model extends CI_Model {

	public function all() {
		return $this->db->get("budgetplan")->result_array();
	}

	public function save($object) {
		return $this->db->insert("budgetplan", $object);
	}

	public function get($attr, $value) {
		return $this->db->get_where("budgetplan", array($attr => $value))->row_array();
	}

	public function update($object) {
		$this->db->where('id', $object['id']);
		return $this->db->update("budgetplan", $object);
	}

	public function delete($id) {
		$this->db->where('id', $id);
		return $this->db->delete('budgetplan');
	}

	public function getBudgetplanStatus($status) {
		$res = $this->db->get_where('budgetplan_status', array('id' => $status))->row_array();
		return $res ? $res['description'] : NULL;
	}

	public function saveExpense($object) {
		return $this->db->insert("expense", $object);
	}

	public function getExpenses($object) {
		return $this->db->get_where("expense", array('budgetplan_id' => $object['id']))->result_array();
	}
}

/* End of file budgetplan_model.php */
/* Location: ./application/models/budgetplan_model.php */ ?>