<?php
class Migration_Cria_tabela_de_permissoes_de_usuario extends CI_migration {

	public function up() {
		// Permission table
		$this->dbforge->add_field(array(
				'id_permission' => array('type' => 'INT','auto_increment' => true),
				'permission_name' => array('type' => 'varchar(20)')
		));
		$this->dbforge->add_key('id_permission', true);
		$this->dbforge->create_table('permission');

		// Permission values
		$object = array('id_permission' => 1, 'permission_name' => 'cadastro');
		$this->db->insert('permission', $object);
		$object = array('id_permission' => 2, 'permission_name' => 'funcionarios');
		$this->db->insert('permission', $object);
		$object = array('id_permission' => 3, 'permission_name' => 'setores');
		$this->db->insert('permission', $object);
		$object = array('id_permission' => 4, 'permission_name' => 'funcoes');
		$this->db->insert('permission', $object);
		$object = array('id_permission' => 5, 'permission_name' => 'departamentos');
		$this->db->insert('permission', $object);
		$object = array('id_permission' => 6, 'permission_name' => 'cursos');
		$this->db->insert('permission', $object);
	}

	public function down() {
		$this->dbforge->drop_table('permission');
	}
}
