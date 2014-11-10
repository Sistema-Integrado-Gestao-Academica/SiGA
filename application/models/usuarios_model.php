<?php 
class Usuarios_model extends CI_Model {
	public function salva($usuario) {
		$this->db->insert("users", $usuario);
	}

	public function buscaPorLoginESenha($login, $senha = "0") {
		$this->db->where("login", $login);
		if ($senha) {
			$this->db->where("password", md5($senha));
		}
		$usuario = $this->db->get("users")->row_array();
		return $usuario;
	}

	public function buscaTodos() {
		return $this->db->get('users')->result_array();
	}

	public function busca($str, $atributo) {
		$this->db->where($str, $atributo);
		$usuario = $this->db->get("users")->row_array();
		return $usuario;
	}

	public function altera($usuario) {
		$this->db->where('login', $usuario['login']);
		$res = $this->db->update("users", array(
			'nome' => $usuario['nome'],
			'email' => $usuario['email'],
			'senha' => $usuario['senha']
		));

		return $res;
	}

	public function remove($usuario) {		
		$res = $this->db->delete("users", array("login" => $usuario['login']));
		return $res;
	}
	
	public function getUserTypes(){
		$this->db->select('id_type, type_name');
		$this->db->from('user_type');
		$types = $this->db->get()->result_array();
		return $types;
	}
}