<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Cria_grupo_coordenador extends CI_Migration {

	public function up() {

		$this->db->insert('group', array(
			'id_group' => 9,
			'group_name' => "coordenador",
			'profile_route' => "coordinator_home"
		));
	}

	public function down() {
		
		$this->db->delete('group', array(
			'id_group' => 9,
			'group_name' => "coordenador"
		));
	}

}
