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

	public function getBudgetplanStatus($status) {
		$res = $this->db->get_where('budgetplan_status', array('id' => $status))->row_array();
		return $res ? $res['description'] : NULL;
	}

	public function saveExpense($object) {
		return $this->db->insert("expense", $object);
	}
}

/* End of file budgetplan_model.php */
/* Location: ./application/models/budgetplan_model.php */ ?>