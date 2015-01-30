<?php
class Migration_Cria_tabela_de_disciplinas extends CI_migration {

	public function up() {
		// Discipline table
		$this->dbforge->add_field(array(
				'discipline_code' => array('type' => 'INT'),
				'discipline_name' => array('type' => 'varchar(20)'),
				'credits'		  => array('type' => 'INT'),
				'workload'	 	  => array('type' => 'INT')
		));

		$this->dbforge->add_key('discipline_code', true);
		$this->dbforge->create_table('discipline', true);

	}

	public function down() {
		$this->dbforge->drop_table('discipline');
	}
}
