<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Cria_tabela_programas_e_adiciona_atributos_de_cursos extends CI_Migration {

	public function up() {

		// Add attributes to course table
		$courseFields = array(
			'total_credits' => array('type' => 'INT'),
			'duration'  => array('type' => 'INT'),
			'workload'  => array('type' => 'INT'),
			'start_class' => array('type' => 'varchar(6)'),
			'description' => array('type' => 'text')
		);
		$this->dbforge->add_column('course', $courseFields);

		// Creating program table and add the needed constraints
		$this->dbforge->add_field(array(
			'id_program'  => array('type' => 'INT', 'auto_increment' => TRUE),
			'program_name' => array('type' => 'varchar(40)'),
			'acronym'  => array('type' => 'varchar(6)'),
			'coordinator' => array('type' => 'INT'),
			'opening_year' => array('type' => 'YEAR')
		));
		$this->dbforge->add_key('id_program', TRUE);
		$this->dbforge->create_table('program', TRUE);

		$program_name_uk = "ALTER TABLE program ADD CONSTRAINT PROGRAM_NAME_UK UNIQUE (program_name)";
		$this->db->query($program_name_uk);

		$program_acronym_uk = "ALTER TABLE program ADD CONSTRAINT PROGRAM_ACRONYM_UK UNIQUE (acronym)";
		$this->db->query($program_acronym_uk);

		$coordinator_fk = "ALTER TABLE program ADD CONSTRAINT PROGRAM_USER_FK FOREIGN KEY (coordinator) REFERENCES users(id)";
		$this->db->query($coordinator_fk);

		// Creating relation table of course and program
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

	public function down() {
		
		$courseFields = array('duration', 'total_credits', 'workload', 'start_class', 'description');

		foreach($courseFields as $column){
			$this->dbforge->drop_column('course', $column);
		}

		$this->dbforge->drop_table('program_course');
		$this->dbforge->drop_table('program');
	}

}
