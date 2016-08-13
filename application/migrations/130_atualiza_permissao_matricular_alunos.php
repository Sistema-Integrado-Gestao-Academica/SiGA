<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Atualiza_permissao_matricular_alunos extends CI_Migration {

	public function up() {
		
		// Updating student permission
		$this->db->where('id_permission', 15);
		$this->db->update('permission', array('permission_name' => "Vincular Alunos"));

	}

	public function down() {
	}
}

?>

