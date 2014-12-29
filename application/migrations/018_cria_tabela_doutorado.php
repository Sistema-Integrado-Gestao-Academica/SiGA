<?php
class Migration_Cria_tabela_de_doutorado extends CI_migration {

	public function up() {
		$this->dbforge->add_field(array(
				'id_course' => array('type' => 'INT'),
				'id_academic_program' => array('type' => 'INT')
		));
		$this->dbforge->add_key('id_course', true);
		$this->dbforge->add_key('id_academic_program', true);
		$this->dbforge->create_table('graduation');
	}

	public function down() {
		$this->dbforge->drop_table('graduation');
	}
}
