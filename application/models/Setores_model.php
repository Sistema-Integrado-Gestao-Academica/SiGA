<?php
class Setores_model extends CI_Model {

	public function busca($atributo, $setor) {
		$res = $this->db->get_where("setores", array($atributo => $setor[$atributo]))->row_array();
		$res = checkArray($res);
		return $res;
	}

	public function buscaTodos() {
		$res = $this->db->get("setores")->result_array();
		$res = checkArray($res);
		return $res;
	}

	public function salva($setor) {
		$this->db->insert("setores", $setor);
	}

	public function altera($id, $nome) {
		$this->db->where('id', $id);
		$res = $this->db->update("setores", array('nome' => $nome));
		return $res;
	}

	public function remove($id) {
		$res = $this->db->delete("setores", array('id' => $id));
		var_dump($this->db->last_query());
		return $res;
	}
}
