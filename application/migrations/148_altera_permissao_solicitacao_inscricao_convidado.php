<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_altera_permissao_solicitacao_inscricao_convidado extends CI_Migration {

	public function up() {
		
		#creating permission
		$this->db->where('route', "guest_home");
		$this->db->update('permission', array('permission_name' => "Solicitar Vínculo em Curso"));
	}

	public function down() {}

}
		