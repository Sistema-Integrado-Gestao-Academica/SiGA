<?php
class Migration_Cria_tabela_de_curso_programa_academico extends CI_migration {

	public function up() {
		$this->dbforge->add_field(array(
				'id_course' => array('type' => 'INT'),
				'duration'  => array('type' => 'INT'),
				'total_credits' => array('type' => 'INT'),
				'workload'  => array('type' => 'INT'),
				'start_class' => array('type' => 'varchar(6)'),
				'description' => array('type' => 'text')
		));
		$this->dbforge->add_key('id_course', true);
		$this->dbforge->create_table('academic_program');
	}

	public function down() {
		$this->dbforge->drop_table('academic_program');
	}
}
