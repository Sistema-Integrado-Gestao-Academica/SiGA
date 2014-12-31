<?php 
class Migration_Cria_tabela_de_usuarios extends CI_migration {
	
	public function up() {
		// User table
		$this->dbforge->add_field(array(
			'id' => array('type' => 'INT','auto_increment' => true),
			'name' => array('type' => 'varchar(70)'),
			'cpf' => array('type' => 'varchar(11)'),
			'email' => array('type' => 'varchar(50)'),
			'login' => array('type' => 'varchar(20)'),
			'password' => array('type' => 'varchar(255)')
		));
		$this->dbforge->add_key('id', true);
		$this->dbforge->create_table('users');

		// User type table
		$this->dbforge->add_field(array(
			'id_type' => array('type' => 'INT','auto_increment' => true),
			'type_name' => array('type' => 'varchar(15)')
		));
		$this->dbforge->add_key('id_type', true);
		$this->dbforge->create_table('user_type');

		// User type values
		$object = array('id_type' => 1, 'type_name' => 'administrador');
		$this->db->insert('user_type', $object);
		$object = array('id_type' => 2, 'type_name' => 'discente');
		$this->db->insert('user_type', $object);
		$object = array('id_type' => 3, 'type_name' => 'docente');
		$this->db->insert('user_type', $object);
		$object = array('id_type' => 4, 'type_name' => 'secretaria');
		$this->db->insert('user_type', $object);
		$object = array('id_type' => 5, 'type_name' => 'convidado');
		$this->db->insert('user_type', $object);

		// Relation of user and user type tables
		$this->dbforge->add_field(array(
			'id_user' => array('type' => 'INT'),
			'id_user_type' => array('type' => 'INT')
		));
		$this->dbforge->add_key('id_user', true);
		$this->dbforge->add_key('id_user_type', true);
		$this->dbforge->create_table('user_user_type');
	}

	public function down() {
		$this->dbforge->drop_table('user_user_type');
		$this->dbforge->drop_table('user_type');
		$this->dbforge->drop_table('users');
	}
}