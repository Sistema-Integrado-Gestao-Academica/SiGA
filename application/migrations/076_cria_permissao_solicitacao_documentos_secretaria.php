<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Cria_permissao_solicitacao_documentos_secretaria extends CI_Migration {

	public function up() {

		$this->db->insert('permission', array(
			'id_permission' => 24,
			'permission_name' => "Solicitações de Documentos",
			'route' => "documents_report"
		));

		// Add the new permission to the student group
		$this->db->insert('group_permission', array(
			'id_group' => 11,
			'id_permission' => 24
		));
	}

	public function down(){
		
	}
}
