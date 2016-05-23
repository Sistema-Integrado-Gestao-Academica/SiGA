<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Cria_permissao_orientador extends CI_Migration {

	public function up() {

		$this->db->insert('permission', array(
			'id_permission' => 12,
			'permission_name' => "Orientador",
			'route' => "mastermind"
		));
		
		$this->db->insert('group_permission', array(
				'id_group' => 5,
				'id_permission' => 12
		));
		
	}

	public function down() {
		
		$this->db->delete('permission', array(
			'id_permission' => 12,
			'permission_name' => "Orientador"
			));
		
		$this->db->delete('group_permission', array(
				'id_group' => 5,
				'id_permission' => 12,
		));
	}

}