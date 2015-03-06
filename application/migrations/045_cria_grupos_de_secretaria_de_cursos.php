<?php
class Migration_Cria_grupos_de_secretaria_de_cursos extends CI_migration {

	public function up() {

		// New group values
		$object = array('id_group' => 10, 'group_name' => 'courseSecretaryFinancial', 'profile_route' => 'secretary_home');
		$this->db->insert('group', $object);
		$object = array('id_group' => 11, 'group_name' => 'courseSecretaryAcademic', 'profile_route' => 'secretary_home');
		$this->db->insert('group', $object);
		
		
		/**
		 * New group permissions
		 */
		// For Financial Secretary
		$object = array('id_group' => 10, 'id_permission' => 2);
		$this->db->insert('group_permission', $object);
		$object = array('id_group' => 10, 'id_permission' => 4);
		$this->db->insert('group_permission', $object);
		$object = array('id_group' => 10, 'id_permission' => 7);
		$this->db->insert('group_permission', $object);
		
		// For Academic Secretary
		$object = array('id_group' => 11, 'id_permission' => 2);
		$this->db->insert('group_permission', $object);
		$object = array('id_group' => 11, 'id_permission' => 3);
		$this->db->insert('group_permission', $object);
		$object = array('id_group' => 11, 'id_permission' => 4);
		$this->db->insert('group_permission', $object);
		$object = array('id_group' => 11, 'id_permission' => 5);
		$this->db->insert('group_permission', $object);
		$object = array('id_group' => 11, 'id_permission' => 6);
		$this->db->insert('group_permission', $object);
		$object = array('id_group' => 11, 'id_permission' => 9);
		$this->db->insert('group_permission', $object);
		
		
		$this->db->delete('group',array('id_group'=>'4'));
	}

	public function down() {
		$this->db->delete('group',array('id_group'=>'10'));
		$this->db->delete('group',array('id_group'=>'11'));
	}
}
