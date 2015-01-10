<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Cria_permissao_estudante extends CI_Migration {

	public function up() {
		
		// Creating student permission
		$student_permission = array('id_permission' => 8, 'permission_name' => "Estudante", 'route' => "student");
		$this->db->insert('permission', $student_permission);

		// Creating student group
		$student_group = array('id_group' => 4, 'group_name' => "estudante");
		$this->db->insert('group', $student_group);

		// Adding student permission into student group
		$student_group_permission = array('id_group' => 4, 'id_permission' => 8);
		$this->db->insert('group_permission', $student_group_permission);

	}

	public function down() {

		$object = array('id_permission' => 8, 'permission_name' => "Estudante", 'route' => "student");
		$this->db->delete('permission', $object);

		$object = array('id_group' => 4, 'group_name' => "estudante");
		$this->db->delete('group', $object);
		
		$object = array('id_group' => 4, 'id_permission' => 8);
		$this->db->delete('group_permission', $object);
	}
}

/* End of file 014_cria_tabela_de_plano_orcamentario.php */
/* Location: ./application/migrations/014_cria_tabela_de_plano_orcamentario.php */ ?>
