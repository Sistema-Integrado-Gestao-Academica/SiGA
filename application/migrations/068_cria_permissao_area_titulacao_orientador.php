<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Cria_permissao_area_titulacao_orientador extends CI_Migration {

	public function up() {

		$this->db->insert('permission', array(
			'id_permission' => 21,
			'permission_name' => "Atualizar titulação",
			'route' => "titling_area"
		));

		$this->db->insert('group_permission', array(
			
			'id_group' => 5,
			'id_permission' => 21
		));
	}

	public function down(){
		
	}
}
