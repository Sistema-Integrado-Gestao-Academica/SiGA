<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Cria_permissao_solicitar_documentos extends CI_Migration {

	public function up() {

		// Change the permission name
		$this->db->where('id_permission', 17);
		$this->db->update('permission', array('permission_name' => "Solicitações de matrícula"));

		$this->db->insert('permission', array(
			'id_permission' => 23,
			'permission_name' => "Solicitação de Documentos",
			'route' => "documents_request"
		));

		// Add the new permission to the student group
		$this->db->insert('group_permission', array(
			'id_group' => 7,
			'id_permission' => 23
		));
	}

	public function down(){
		
	}
}
