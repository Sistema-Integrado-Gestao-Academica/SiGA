<?php
class Migration_Cria_tabela_de_grupos_de_usuarios extends CI_migration {

	public function up() {
		$this->dbforge->add_field(array(
			'id_user' => array('type' => 'INT'),
			'id_group' => array('type' => 'INT')
		));
		$this->dbforge->add_key('id_user');
		$this->dbforge->add_key('id_group');
		$this->dbforge->create_table('user_group');
	}

	public function down() {
		$this->dbforge->drop_table('user_group');
	}
}
