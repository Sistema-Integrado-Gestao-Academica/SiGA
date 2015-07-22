<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Cria_permissao_programa extends CI_Migration {

	public function up() {

		// Create program permission
		$this->db->insert('permission', array(
			'id_permission' => 14,
			'permission_name' => 'Programas',
			'route' => 'program'
		));

		// Add the program permission to the Admin
		$this->db->insert('group_permission', array(
			'id_group' => 3,
			'id_permission' => 14
		));
	}

	public function down(){

		$this->db->delete('group_permission', array('id_permission' => 14));
		$this->db->delete('permission', array('id_permission' => 14));
	}
}
