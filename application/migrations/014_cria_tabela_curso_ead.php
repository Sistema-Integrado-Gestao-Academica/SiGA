<?php
class Migration_Cria_tabela_de_curso_ead extends CI_migration {

	public function up() {
		$this->dbforge->add_field(array(
				'id_course' => array('type' => 'INT')
		));
		$this->dbforge->add_key('id_course', true);
		$this->dbforge->create_table('ead');
	}

	public function down() {
		$this->dbforge->drop_table('ead');
	}
}
