<?php 
class Usuarios_model extends CI_Model {
	public function salva($usuario) {
		$this->db->insert("users", $usuario);
	}

	public function saveType($user, $type){
		$rowUser = $this->buscaPorLoginESenha($user['login']);
		$user_id = $rowUser['id'];
		
		$user_user_type = array("id_user"=>$user_id,"id_user_type"=>$type);
		
		$this->db->insert("user_user_type",$user_user_type);
	}

	public function buscaPorLoginESenha($login, $senha = "0") {
		$this->db->where("login", $login);
		if ($senha) {
			$this->db->where("password", md5($senha));
		}

		// Select here the data from user to put on the session
		$this->db->select('id, name, email, login');
		$usuario = $this->db->get("users")->row_array();
		
		return $usuario;
	}

	/**
	 * Get the registered user types for an given user id
	 * @param $user_id - The user id to look for types
	 * @return An array with the user types id's in each position of the array
	 */
	public function getUserType($user_id){
		
		$this->db->select('id_user_type');
		$types_found = $this->db->get_where("user_user_type", array('id_user'=>$user_id));
		$types_found_to_array = $types_found->result_array();
		
		// Filter the array returned from result_array() into a single array
		for($i = 0; $i < sizeof($types_found_to_array); $i++){
			$user_types_found[$i] = $types_found_to_array[$i]['id_user_type'];

			$this->db->select('type_name');
			$type_name = $this->db->get_where("user_type",array('id_type'=>$user_types_found[$i]))->result_array();
			if($type_name){
				$user_type_name[$i] = $type_name[0]['type_name'];
			}
		}
		
		$user_type_return = array_merge($user_types_found,$user_type_name);
		return $user_type_return;
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
		$this->db->where('login', $usuario['user']['login']);
		$res = $this->db->update("users", array(
			'name' => $usuario['user']['name'],
			'email' => $usuario['user']['email'],
			'password' => $usuario['user']['password']
		));

		return $res;
	}

	public function remove($usuario) {		
		$res = $this->db->delete("users", array("login" => $usuario['login']));
		return $res;
	}
	
	public function getAllUserTypes(){

		$this->db->select('id_type, type_name');
		$this->db->from('user_type');
		$userTypes = $this->db->get()->result_array();
		
		return $userTypes;
	}

	/**
	  * Check if a given user type id is the admin id.
	  * @param $id_to_check - User type id to check
	  * @return True if the id is of the admin, or false if does not.
	  */
	public function checkIfIdIsOfAdmin($id_to_check){
		
		// The administer name on database
		define("ADMINISTER", "administrador");

		$this->db->select('type_name');
		$this->db->from('user_type');
		$this->db->where('id_type', $id_to_check);
		$tuple_found = $this->db->get()->row();

		$type_name_found = $tuple_found->type_name;

		if($type_name_found === ADMINISTER){
			$isAdmin = TRUE;
		}else{
			$isAdmin = FALSE;
		}

		return $isAdmin;
	}
}
