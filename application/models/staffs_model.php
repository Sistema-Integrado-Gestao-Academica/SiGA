<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class staffs_model extends CI_Model {

	public function busca($atributo, $setor) {
		$res = $this->db->get_where("staffs", array($atributo => $setor[$atributo]))->row_array();
		$res = checkArray($res);
		return $res;
	}

	public function getAllStaffs() {
		$allStaffs = $this->db->get("staffs")->result_array();
		$allStaffs = checkArray($allStaffs);
		return $allStaffs;
	}

	public function salva($setor) {
		$this->db->insert("staffs", $setor);
	}

	public function altera($id, $nome) {
		$this->db->where('id', $id);
		$res = $this->db->update("staffs", array('nome' => $nome));
		return $res;
	}

	public function remove($id) {
		$res = $this->db->delete("staffs", array('id' => $id));
		var_dump($this->db->last_query());
		return $res;
	}
}

/* End of file staffs_model.php */
/* Location: ./application/models/staffs_model.php */ ?>