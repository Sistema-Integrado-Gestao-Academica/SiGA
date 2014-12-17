<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Budgetplan_model extends CI_Model {

	public function all() {
		return $this->db->get("budgetplan")->result_array();
	}

	public function getBudgetplanStatus($status) {
		$res = $this->db->get_where('budgetplan_status', array('id' => $status))->row_array();
		return $res ? $res['description'] : NULL;
	}

	public function save($object) {
		return $this->db->insert("budgetplan", $object);
	}

	public function get($attr, $object) {
		return $this->db->get_where("budgetplan", array($attr => $object[$attr]))->row_array();
	}

	public function update($id, $object) {
		$this->db->where('id', $id);
		return $this->db->update("budgetplan", $object);
	}

}

/* End of file budgetplan_model.php */
/* Location: ./application/models/budgetplan_model.php */ ?>