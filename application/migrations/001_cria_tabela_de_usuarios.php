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

	// Inserting user values
		$user_password = md5("admin");
		$user_values = array('id' => '1', 'name' => 'admin', 'cpf' => '04106996178', 'email' => 'admin@admin.com', 'login' => 'admin', 'password' => $user_password);
		$this->db->insert('users', $user_values);
		
		$user_password = md5("italo");
		$user_values = array('id' => '2', 'name' => 'Italo', 'cpf' => '13506796140', 'email' => 'italo@italo.com', 'login' => 'italo', 'password' => $user_password);
		$this->db->insert('users', $user_values);
		
		$user_password = md5("macario");
		$user_values = array('id' => '3', 'name' => 'Macario', 'cpf' => '89906996153', 'email' => 'macario@macario.com', 'login' => 'macario', 'password' => $user_password);
		$this->db->insert('users', $user_values);

		$user_password = md5("fillipe");
		$user_values = array('id' => '4', 'name' => 'Fillipe', 'cpf' => '00303596168', 'email' => 'fillipe@fillipe.com', 'login' => 'fillipe', 'password' => $user_password);
		$this->db->insert('users', $user_values);
	// End

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

		$this->dbforge->create_table('user_user_type');

		// Adding the foreign keys constraints
		$add_foreign_key = "ALTER TABLE user_user_type ADD CONSTRAINT IDUSER_USER_TYPE FOREIGN KEY (id_user) REFERENCES users(id)";
		$this->db->query($add_foreign_key);
		
		$add_foreign_key = "ALTER TABLE user_user_type ADD CONSTRAINT IDUSERTYPE_USER_TYPE FOREIGN KEY (id_user_type) REFERENCES user_type(id_type)";
		$this->db->query($add_foreign_key);

	// Inserting user_user_type values
		$user_user_type_value = array('id_user' => 1, 'id_user_type' => 1);
		$this->db->insert('user_user_type', $user_user_type_value);

		$user_user_type_value = array('id_user' => 2, 'id_user_type' => 2);
		$this->db->insert('user_user_type', $user_user_type_value);

		$user_user_type_value = array('id_user' => 3, 'id_user_type' => 3);
		$this->db->insert('user_user_type', $user_user_type_value);

		$user_user_type_value = array('id_user' => 4, 'id_user_type' => 4);
		$this->db->insert('user_user_type', $user_user_type_value);
	// End

	}

	public function down() {
		$this->dbforge->drop_table('user_user_type');
		$this->dbforge->drop_table('user_type');
		$this->dbforge->drop_table('users');
	}
}