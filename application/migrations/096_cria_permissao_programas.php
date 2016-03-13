<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_cria_permissao_programas extends CI_Migration {

	public function up() {
		
		#creating permission
		$this->db->insert('permission', array('permission_name' => "Programas", 'route' => "secretary_programs", "id_permission"=>27));
		
		#creating relation between academic secretary and research lines permission
		$this->db->insert('group_permission', array('id_group' => 11, 'id_permission' => 27));
		
	}

	public function down() {
		

		
	}

}