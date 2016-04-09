<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_cria_permissao_atualizar_perfil_docente extends CI_Migration {

	public function up() {
		
		#creating permission
		$this->db->insert('permission', array('permission_name' => "Atualizar Perfil", 'route' => "update_profile", "id_permission"=>28));
		
		#creating relation between academic secretary and research lines permission
		$this->db->insert('group_permission', array('id_group' => 5, 'id_permission' => 28));
	}

	public function down() {		
	}

}