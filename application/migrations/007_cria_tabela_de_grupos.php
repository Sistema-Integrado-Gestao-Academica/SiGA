<?php
class Migration_Cria_tabela_de_grupos extends CI_migration {

	public function up() {
		// Group table
		$this->dbforge->add_field(array(
			'id_group' => array('type' => 'INT','auto_increment' => true),
			'group_name' => array('type' => 'varchar(25)')
		));
		
		$this->dbforge->add_key('id_group', true);
		$this->dbforge->create_table('group', true);

		// Group values
		$object = array('id_group' => 1, 'group_name' => 'financeiro');
		$this->db->insert('group', $object);
		$object = array('id_group' => 2, 'group_name' => 'administrativo');
		$this->db->insert('group', $object);
		$object = array('id_group' => 3, 'group_name' => 'administrador');
		$this->db->insert('group', $object);
		$object = array('id_group' => 4, 'group_name' => 'discente');
		$this->db->insert('group', $object);
		$object = array('id_group' => 5, 'group_name' => 'docente');
		$this->db->insert('group', $object);
		$object = array('id_group' => 6, 'group_name' => 'secretario');
		$this->db->insert('group', $object);
		$object = array('id_group' => 7, 'group_name' => 'convidado');

	}

	public function down() {
		$this->dbforge->drop_table('group');
	}
}
