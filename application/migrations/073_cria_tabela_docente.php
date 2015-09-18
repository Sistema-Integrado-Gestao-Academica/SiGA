<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_cria_tabela_docente extends CI_Migration {

	public function up() {

		$this->dbforge->add_field(array(
			// 'id_teacher_course' => array('type' => 'INT', 'auto_increment' => TRUE),
			'id_user' => array('type' => 'INT'),
			'id_course' => array('type' => 'INT'),
			'situation' => array('type' => 'varchar(30)', 'null' => TRUE)
		));
		$this->dbforge->create_table('teacher_course', TRUE);
		
		$addConstraint = "ALTER TABLE teacher_course ADD CONSTRAINT TEACHER_COURSE_USER_FK FOREIGN KEY (id_user) REFERENCES users(id) ON DELETE CASCADE ON UPDATE RESTRICT";
		$this->db->query($addConstraint);

		$addConstraint = "ALTER TABLE teacher_course ADD CONSTRAINT COURSE_TEACHER_COURSE_FK FOREIGN KEY (id_course) REFERENCES course(id_course) ON DELETE CASCADE ON UPDATE RESTRICT";
		$this->db->query($addConstraint);

		$uk = "ALTER TABLE teacher_course ADD CONSTRAINT TEACHER_USER_COURSE_UK UNIQUE (id_user, id_course)";
		$this->db->query($uk);
	}

	public function down(){
		
		$dropConstraint = "ALTER TABLE teacher_course DROP FOREIGN KEY TEACHER_COURSE_USER_FK";
		$this->db->query($dropConstraint);

		$dropConstraint = "ALTER TABLE teacher_course DROP FOREIGN KEY COURSE_TEACHER_COURSE_FK";
		$this->db->query($dropConstraint);
		
		$this->dbforge->drop_table('teacher_course');
		
	}
}
