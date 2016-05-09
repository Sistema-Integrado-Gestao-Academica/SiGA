<?php
class Migration_Cria_tabela_de_relacao_entre_grupos_e_permissoes extends CI_migration {

	public function up() {
		// Group permission table
		$this->dbforge->add_field(array(
				'id_group' => array('type' => 'INT'),
				'id_permission' => array('type' => 'INT')
		));
		$this->dbforge->create_table('group_permission');

		// $add_foreign_key = "ALTER TABLE group_permission ADD CONSTRAINT IDGROUP_GROUPPERMIS_FK FOREIGN KEY (id_group) REFERENCES group (id_group)";
		// $this->db->query($add_foreign_key);
		
		$add_foreign_key = "ALTER TABLE group_permission ADD CONSTRAINT IDPERMIS_GROUPPERMIS_FK FOREIGN KEY (id_permission) REFERENCES permission (id_permission)";
		$this->db->query($add_foreign_key);

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
		$object = array('id_group' => 6, 'id_permission' => 6);
		$this->db->insert('group_permission', $object);
	}

	public function down() {
		$this->dbforge->drop_table('group_permission');
	}
}
