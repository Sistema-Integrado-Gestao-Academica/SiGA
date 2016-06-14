<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_cria_permissao_natureza_despesa extends CI_Migration {

	public function up() {
		
		// Creating permission
		$this->db->insert('permission', array('permission_name' => "Naturezas de despesa", 'route' => "expense_nature", "id_permission"=>32));
		
		// Adding permission to finantial secretary
		$this->db->insert('group_permission', array('id_group' => 10, 'id_permission' => 32));
	}

	public function down() {
		
	}

}