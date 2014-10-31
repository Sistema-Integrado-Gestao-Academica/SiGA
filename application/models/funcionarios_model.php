<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Funcionarios_model extends CI_Model {

	public function busca($atributo, $setor) {
		$res = $this->db->get_where("funcionarios", array($atributo => $setor[$atributo]))->row_array();
		return $res;
	}

	public function buscaTodos() {
		return $this->db->get("funcionarios")->result_array();
	}

	public function salva($setor) {
		$this->db->insert("funcionarios", $setor);
	}

	public function altera($id, $nome) {
		$this->db->where('id', $id);
		$res = $this->db->update("funcionarios", array('nome' => $nome));
		return $res;
	}

	public function remove($id) {
		$res = $this->db->delete("funcionarios", array('id' => $id));
		var_dump($this->db->last_query());
		return $res;
	}
}

/* End of file funcionarios_model.php */
/* Location: ./application/models/funcionarios_model.php */ ?>