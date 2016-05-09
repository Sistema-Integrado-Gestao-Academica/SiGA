<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Cria_permissao_estudante extends CI_Migration {

	public function up() {
		
		// Creating student permission
		$student_permission = array('id_permission' => 8, 'permission_name' => "Estudante", 'route' => "student");
		$this->db->insert('permission', $student_permission);

		// Creating student group
		$student_group = array('id_group' => 7, 'group_name' => "estudante");
		$this->db->insert('group', $student_group);

		// Adding student permission into student group
		$student_group_permission = array('id_group' => 7, 'id_permission' => 8);
		$this->db->insert('group_permission', $student_group_permission);

		// Add the student permission to the admin
		$student_permission_to_admin = array('id_user' => 1, 'id_group' => 7);
		$this->db->insert('user_group', $student_permission_to_admin);

		// Add the student permission to the user 2
		$student_permission = array('id_user' => 2, 'id_group' => 7);
		$this->db->insert('user_group', $student_permission);
	}

	public function down() {

		$object = array('id_permission' => 8, 'permission_name' => "Estudante", 'route' => "student");
		$this->db->delete('permission', $object);

		$object = array('id_group' => 7, 'group_name' => "estudante");
		$this->db->delete('group', $object);
		
		$object = array('id_group' => 7, 'id_permission' => 8);
		$this->db->delete('group_permission', $object);
	}
}

?>

