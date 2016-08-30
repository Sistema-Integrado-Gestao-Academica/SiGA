<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_cria_permissao_disciplinas_ofertadas extends CI_Migration {

	public function up() {

		#creating permission
		$this->db->insert('permission', array('permission_name' => "Relatório Geral de Matrícula", 'route' => "enrollment_report", "id_permission"=>36));

		#creating relation between academic secretary and offered disciplines permission
		$this->db->insert('group_permission', array('id_group' => 11, 'id_permission' => 36));
	}

	public function down() {

	}

}