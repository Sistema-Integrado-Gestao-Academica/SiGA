<?php
class Migration_Cria_tabela_de_tipos_de_usuarios extends CI_migration {

	public function up() {
		$this->dbforge->add_field(array(
				'id_type' => array('type' => 'INT','auto_increment' => true),
				'type_name' => array('type' => 'varchar(15)')
		));
		$this->dbforge->add_key('id_user', true);
		$this->dbforge->create_table('user_type');
	}

	public function down() {
		$this->dbforge->drop_table('user_type');
	}
}
