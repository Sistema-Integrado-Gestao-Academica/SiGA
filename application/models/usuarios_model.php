<?php 
class Usuarios_model extends CI_Model {
	public function salva($usuario) {
		$this->db->insert("usuarios", $usuario);
	}

	public function buscaPorLoginESenha($login, $senha = "0") {
		$this->db->where("login", $login);
		if ($senha) {
			$this->db->where("senha", md5($senha));
		}
		$usuario = $this->db->get("usuarios")->row_array();
		return $usuario;
	}

	public function buscaTodos() {
		return $this->db->get('usuarios')->result_array();
	}

	public function busca($str, $atributo) {
		$this->db->where($str, $atributo);
		$usuario = $this->db->get("usuarios")->row_array();
		return $usuario;
	}

	public function altera($usuario) {
		$this->db->where('login', $usuario['login']);
		$res = $this->db->update("usuarios", array(
			'nome' => $usuario['nome'],
			'email' => $usuario['email'],
			'senha' => $usuario['senha']
		));

		return $res;
	}

	public function remove($usuario) {		
		$res = $this->db->delete("usuarios", array("login" => $usuario['login']));
		return $res;
	}
}