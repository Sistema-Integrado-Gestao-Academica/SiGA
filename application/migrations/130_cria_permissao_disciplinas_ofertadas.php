<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_cria_permissao_disciplinas_ofertadas extends CI_Migration {

	public function up() {

		#creating permission
		$this->db->insert('permission', array('permission_name' => "Disciplinas Ofertadas", 'route' => "offered_disciplines", "id_permission"=>35));

		#creating relation between academic secretary and offered disciplines permission
		$this->db->insert('group_permission', array('id_group' => 11, 'id_permission' => 35));

	}

	public function down() {



	}

}