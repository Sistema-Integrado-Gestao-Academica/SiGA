<?php 
class Migration_Cria_tabela_de_usuarios extends CI_migration {
	
	public function up() {
		$this->dbforge->add_field(array(
			'id' => array('type' => 'INT','auto_increment' => true),
			'name' => array('type' => 'varchar(70)'),
			'cpf' => array('type' => 'varchar(11)'),
			'email' => array('type' => 'varchar(50)'),
			'login' => array('type' => 'varchar(20)'),
			'password' => array('type' => 'varchar(255)')
		));
		$this->dbforge->add_key('id', true);
		$this->dbforge->create_table('user');
	}

	public function down() {
		$this->dbforge->drop_table('user');
	}
}