<?php
class Migration_Cria_tabela_de_relacao_entre_grupos_e_permissoes extends CI_migration {

	public function up() {
		// Group permission table
		$this->dbforge->add_field(array(
				'id_group' => array('type' => 'INT'),
				'id_permission' => array('type' => 'INT')
		));
		$this->dbforge->add_key('id_group');
		$this->dbforge->add_key('id_permission');
		$this->dbforge->create_table('group_permission');

		// Group permission values
		$object = array('id_group' => 1, 'id_permission' => 2);
		$this->db->insert('group_permission', $object);
		$object = array('id_group' => 1, 'id_permission' => 4);
		$this->db->insert('group_permission', $object);
		$object = array('id_group' => 2, 'id_permission' => 2);
		$this->db->insert('group_permission', $object);
		$object = array('id_group' => 2, 'id_permission' => 3);
		$this->db->insert('group_permission', $object);
		$object = array('id_group' => 2, 'id_permission' => 4);
		$this->db->insert('group_permission', $object);
		$object = array('id_group' => 2, 'id_permission' => 5);
		$this->db->insert('group_permission', $object);
		$object = array('id_group' => 2, 'id_permission' => 6);
		$this->db->insert('group_permission', $object);
		$object = array('id_group' => 3, 'id_permission' => 1);
		$this->db->insert('group_permission', $object);
		$object = array('id_group' => 3, 'id_permission' => 2);
		$this->db->insert('group_permission', $object);
		$object = array('id_group' => 3, 'id_permission' => 3);
		$this->db->insert('group_permission', $object);
		$object = array('id_group' => 3, 'id_permission' => 4);
		$this->db->insert('group_permission', $object);
		$object = array('id_group' => 3, 'id_permission' => 5);
		$this->db->insert('group_permission', $object);
		$object = array('id_group' => 3, 'id_permission' => 6);
		$this->db->insert('group_permission', $object);
	}

	public function down() {
		$this->dbforge->drop_table('group_permission');
	}
}
