<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Transforma_relacao_programa_curso_1_N extends CI_Migration {

	public function up() {

		$field = array(
			'id_program' => array('type' => 'INT', 'null' => TRUE)
		);

		$this->dbforge->add_column('course', $field);

		$fk = "ALTER TABLE course ADD CONSTRAINT IDPROGRAM_COURSE_FK FOREIGN KEY (id_program) REFERENCES program(id_program)";
		$this->db->query($fk);
		
		// Dropping program_course table		
		$drop_fk = "ALTER TABLE program_course DROP FOREIGN KEY PROGRAMCOURSE_IDPROGRAM_FK";
		$this->db->query($drop_fk);

		$drop_fk = "ALTER TABLE program_course DROP FOREIGN KEY PROGRAMCOURSE_IDCOURSE_FK";
		$this->db->query($drop_fk);

		$this->dbforge->drop_table('program_course');
	}

	public function down(){
		
		$drop_fk = "ALTER TABLE program_course DROP FOREIGN KEY IDPROGRAM_COURSE_FK";
		$this->db->query($drop_fk);

		$this->dbforge->drop_column('course', 'id_program');
		
		$this->dbforge->add_field(array(
			'id_program'  => array('type' => 'INT'),
			'id_course'  => array('type' => 'INT')
		));
		$this->dbforge->create_table('program_course', TRUE);

		$program_fk = "ALTER TABLE program_course ADD CONSTRAINT PROGRAMCOURSE_IDPROGRAM_FK FOREIGN KEY (id_program) REFERENCES program(id_program)";
		$this->db->query($program_fk);

		$course_fk = "ALTER TABLE program_course ADD CONSTRAINT PROGRAMCOURSE_IDCOURSE_FK FOREIGN KEY (id_course) REFERENCES course(id_course)";
		$this->db->query($course_fk);

	}
}
