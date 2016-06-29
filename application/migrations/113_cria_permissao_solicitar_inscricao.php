<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_cria_permissao_solicitar_inscricao extends CI_Migration {

	public function up() {
		
		#creating permission
		$this->db->insert('permission', array('permission_name' => "Solicitar Inscrição", 'route' => "guest_home", "id_permission"=>30));
		
		#creating relation between guest and apply for registration permission
		$this->db->insert('group_permission', array('id_group' => 8, 'id_permission' => 30));
		
	}

	public function down() {
		

		
	}

}