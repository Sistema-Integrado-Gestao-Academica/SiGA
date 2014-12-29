<?php
class Migration_Cria_tabela_de_permissoes_de_usuario extends CI_migration {

	public function up() {
		$this->dbforge->add_field(array(
				'id_permission' => array('type' => 'INT','auto_increment' => true),
				'permission_name' => array('type' => 'varchar(20)'),
				'permission_description' => array('type' => 'text')
		));
		$this->dbforge->add_key('id_permission', true);
		$this->dbforge->create_table('permission');
	}

	public function down() {
		$this->dbforge->drop_table('permission');
	}
}
