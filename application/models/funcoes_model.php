<?php
class Funcoes_model extends CI_Model {

	public function busca($atributo, $funcao) {
		$res = $this->db->get_where("funcoes", array($atributo => $funcao[$atributo]))->row_array();
		return $res;
	}

	public function buscaTodos() {
		return $this->db->get("funcoes")->result_array();
	}

	public function salva($funcao) {
		$this->db->insert("funcoes", $funcao);
	}

	public function altera($id, $nome) {
		$this->db->where('id', $id);
		$res = $this->db->update("funcoes", array('nome' => $nome));
		return $res;
	}

	public function remove($id) {
		$res = $this->db->delete("funcoes", array('id' => $id));
		var_dump($this->db->last_query());
		return $res;
	}
}
