<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Cria_tabela_curriculo extends CI_Migration {

	public function up() {

		// Course Syllabus table
		$this->dbforge->add_field(array(
			'id_syllabus' => array('type' => 'INT', 'auto_increment' => true),
			'id_course' => array('type' => 'INT')
		));

		$this->dbforge->add_key('id_syllabus', true);
		$this->dbforge->create_table('course_syllabus', true);

		$id_course_fk = "ALTER TABLE course_syllabus ADD CONSTRAINT IDCOURSE_SYLLABUS_FK FOREIGN KEY (id_course) REFERENCES course(id_course)";
		$this->db->query($id_course_fk);

		$id_course_uk = "ALTER TABLE course_syllabus ADD CONSTRAINT IDCOURSE_SYLLABUS_UK UNIQUE (id_course)";
		$this->db->query($id_course_uk);
	}

	public function down() {
		
		$id_course_fk = "ALTER TABLE course_syllabus DROP CONSTRAINT IDCOURSE_SYLLABUS_FK";
		$this->db->query($id_course_fk);

		$this->dbforge->drop_table('course_syllabus');
	}

}
