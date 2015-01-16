<?php
class Migration_Cria_tabela_de_relacao_entre_curso_e_usuario extends CI_migration {

	public function up() {
		// Group permission table
		$this->dbforge->add_field(array(
				'id_course' => array('type' => 'INT'),
				'id_user' => array('type' => 'INT'),
				'enrol_date' => array('type' => 'DATETIME')
		));
		$this->dbforge->create_table('course_student');

		$add_foreign_key = "ALTER TABLE course_student ADD CONSTRAINT IDCOURSE_COURSESTUDENT_FK FOREIGN KEY (id_course) REFERENCES course (id_course)";
		$this->db->query($add_foreign_key);
		
		$add_foreign_key = "ALTER TABLE course_student ADD CONSTRAINT IDUSER_COURSESTUDENT_FK FOREIGN KEY (id_user) REFERENCES users (id)";
		$this->db->query($add_foreign_key);

		
	}

	public function down() {

		$drop_foreign_key = "ALTER TABLE course_student DROP FOREIGN KEY IDCOURSE_COURSESTUDENT_FK";
		$this->db->query($drop_foreign_key);

		$drop_foreign_key = "ALTER TABLE course_student DROP FOREIGN KEY IDUSER_COURSESTUDENT_FK";
		$this->db->query($drop_foreign_key);

		$this->dbforge->drop_table('course_student');
	}
}
