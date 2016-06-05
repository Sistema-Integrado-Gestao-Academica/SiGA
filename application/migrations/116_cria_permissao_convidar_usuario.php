<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_cria_permissao_convidar_usuario extends CI_Migration {

	public function up() {
		
		// Creating permission
		$this->db->insert('permission', array('permission_name' => "Convidar usuários", 'route' => "invite_user", "id_permission"=>31));
		
		// Adding permission to academic secretary
		$this->db->insert('group_permission', array('id_group' => 11, 'id_permission' => 31));
		
	}

	public function down() {
		
	}

}