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
		$this->dbforge->create_table('users', true);

	// Inserting user values
		$user_password = md5("admin");
		$user_values = array('id' => '1', 'name' => 'admin', 'cpf' => '11111111111', 'email' => 'admin@admin.com', 'login' => 'admin', 'password' => $user_password);
		$this->db->insert('users', $user_values);
		
		$user_password = md5("italo");
		$user_values = array('id' => '2', 'name' => 'Italo', 'cpf' => '22222222222', 'email' => 'italo@italo.com', 'login' => 'italo', 'password' => $user_password);
		$this->db->insert('users', $user_values);
		
		$user_password = md5("macario");
		$user_values = array('id' => '3', 'name' => 'Macario', 'cpf' => '33333333333', 'email' => 'macario@macario.com', 'login' => 'macario', 'password' => $user_password);
		$this->db->insert('users', $user_values);

		$user_password = md5("fillipe");
		$user_values = array('id' => '4', 'name' => 'Fillipe', 'cpf' => '44444444444', 'email' => 'fillipe@fillipe.com', 'login' => 'fillipe', 'password' => $user_password);
		$this->db->insert('users', $user_values);
	// End

	}

	public function down() {
		$this->dbforge->drop_table('users');
	}
}