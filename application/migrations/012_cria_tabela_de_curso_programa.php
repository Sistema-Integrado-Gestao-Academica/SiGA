<?php
class Migration_Cria_tabela_de_curso_programa extends CI_migration {

	public function up() {
		// Program table
		$this->dbforge->add_field(array(
			'id' => array('type' => 'INT', 'auto_increment' => true),
			'id_course' => array('type' => 'INT'),
			'duration'  => array('type' => 'INT'),
			'total_credits' => array('type' => 'INT'),
			'workload'  => array('type' => 'INT'),
			'start_class' => array('type' => 'varchar(6)'),
			'description' => array('type' => 'text'),
			'type' => array('type' => 'INT')
		));
		$this->dbforge->add_key('id', true);
		$this->dbforge->add_key('id_course');
		$this->dbforge->add_key('type');
		$this->dbforge->create_table('program');

		// Program type table
		$this->dbforge->add_field(array(
			'id' => array('type' => 'INT', 'auto_increment' => true),
			'description' => array('type' => 'varchar(100)')
		));
		$this->dbforge->add_key('id', true);
		$this->dbforge->create_table('program_type');

		
		// Program type values
		$object = array('id' => 1, 'description' => 'academico');
		$this->db->insert('program_type', $object);
		$object = array('id' => 2, 'description' => 'profissional');
		$this->db->insert('program_type', $object);
	}

	public function down() {
		$this->dbforge->drop_table('program_type');
		$this->dbforge->drop_table('program');
	}
}
