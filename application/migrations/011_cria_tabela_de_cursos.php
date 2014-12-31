<?php
class Migration_Cria_tabela_de_cursos extends CI_migration {

	public function up() {
		// Course table
		$this->dbforge->add_field(array(
				'id_course' => array('type' => 'INT','auto_increment' => true),
				'course_name' => array('type' => 'varchar(40)'),
				'id_course_type' => array('type' => 'INT')
		));
		$this->dbforge->add_key('id_course', true);
		$this->dbforge->add_key('id_course_type');
		$this->dbforge->create_table('course');

		// Course type table
		$this->dbforge->add_field(array(
				'id_course_type' => array('type' => 'INT','auto_increment' => true),
				'course_type_name' => array('type' => 'varchar(40)')
		));
		$this->dbforge->add_key('id_course_type', true);
		$this->dbforge->create_table('course_type');
		
		// Course type values
		$object = array('id_course_type' => 1, 'course_type_name' => 'graduação');
		$this->db->insert('course_type', $object);
		$object = array('id_course_type' => 2, 'course_type_name' => 'pós graduação');
		$this->db->insert('course_type', $object);
		$object = array('id_course_type' => 3, 'course_type_name' => 'educação à distância');
		$this->db->insert('course_type', $object);
	}

	public function down() {
		$this->dbforge->drop_table('course');
		$this->dbforge->drop_table('course_type');
	}
}
