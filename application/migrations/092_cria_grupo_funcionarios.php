<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Cria_grupo_funcionarios extends CI_Migration {

	public function up() {
		
		// Creating guest group
		$guest_group = array('id_group' => 12, 'group_name' => "funcionario");
		$this->db->insert('group', $guest_group);

	}

	public function down() {

		// Deleting guest group
		$guest_group = array('id_group' => 12, 'group_name' => "funcionario");
		$this->db->delete('group', $guest_group);
	}
}

?>

