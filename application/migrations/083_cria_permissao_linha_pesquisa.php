<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_cria_permissao_linha_pesquisa extends CI_Migration {

	public function up() {
		
		#creating permission
		$this->db->insert('permission', array('permission_name' => "Linhas de Pesquisa", 'route' => "research_lines", "id_permission"=>26));
		
		#creating relation between academic secretary and research lines permission
		$this->db->insert('group_permission', array('id_group' => 11, 'id_permission' => 26));
		
	}

	public function down() {
		
		#deleting permission
		$this->db->delete('permission', array('permission_name' => "Linhas de Pesquisa", 'route' => "research_lines", "id_permission"=>26));
		
		#deleting relation between coordinator and reports permission
		$this->db->delete('group_permission', array('id_group' => 11, 'id_permission' => 26));
		
	}

}