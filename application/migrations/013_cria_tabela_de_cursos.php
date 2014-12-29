<?php
class Migration_Cria_tabela_de_cursos extends CI_migration {

	public function up() {
		$this->dbforge->add_field(array(
				'id_course' => array('type' => 'INT','auto_increment' => true),
				'course_name' => array('type' => 'varchar(40)'),
				'course_type' => array('type' => 'varchar(20)')
		));
		$this->dbforge->add_key('id_course', true);
		$this->dbforge->create_table('course');
	}

	public function down() {
		$this->dbforge->drop_table('course');
	}
}
