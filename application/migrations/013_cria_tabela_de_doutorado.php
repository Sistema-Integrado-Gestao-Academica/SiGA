<?php
class Migration_Cria_tabela_de_doutorado extends CI_migration {

	public function up() {
		$this->dbforge->add_field(array(
				'id_doctorate' => array('type' => 'INT'),
				'id_academic_program' => array('type' => 'INT')
		));
		$this->dbforge->add_key('id_doctorate');
		$this->dbforge->add_key('id_academic_program');
		$this->dbforge->create_table('doctorate');
	}

	public function down() {
		$this->dbforge->drop_table('doctorate');
	}
}
