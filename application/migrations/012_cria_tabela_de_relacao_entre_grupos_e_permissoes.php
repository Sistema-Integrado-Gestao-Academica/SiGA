<?php
class Migration_Cria_tabela_de_relacao_entre_grupos_e_premissoes extends CI_migration {

	public function up() {
		$this->dbforge->add_field(array(
				'id_group' => array('type' => 'INT'),
				'id_permission' => array('type' => 'INT')
		));
		$this->dbforge->add_key('id_group', true);
		$this->dbforge->add_key('id_permission', true);
		$this->dbforge->create_table('group_permission');
	}

	public function down() {
		$this->dbforge->drop_table('group_permission');
	}
}
