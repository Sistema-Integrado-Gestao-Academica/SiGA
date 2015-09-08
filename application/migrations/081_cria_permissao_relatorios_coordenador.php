<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_cria_permissao_relatorios_coordenador extends CI_Migration {

	public function up() {
		
		#creating permission
		$this->db->insert('permission', array('permission_name' => "Relatórios Curso", 'route' => "course_report", "id_permission"=>25));
		
		#creating relation between coordinator and reports permission
		$this->db->insert('group_permission', array('id_group' => 9, 'id_permission' => 25));
		
	}

	public function down() {
		
		#deleting permission
		$this->db->delete('permission', array('permission_name' => "Relatórios Curso", 'route' => "course_report", "id_permission"=>25));
		
		#deleting relation between coordinator and reports permission
		$this->db->delete('group_permission', array('id_group' => 9, 'id_permission' => 25));
		
	}

}