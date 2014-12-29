<?php
class Migration_Cria_tabela_de_grupos_de_usuario extends CI_migration {

	public function up() {
		$this->dbforge->add_field(array(
				'id_group' => array('type' => 'INT','auto_increment' => true),
				'group_name' => array('type' => 'varchar(20)')
		));
		$this->dbforge->add_key('id_group', true);
		$this->dbforge->create_table('group');
	}

	public function down() {
		$this->dbforge->drop_table('group');
	}
}
