<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Cria_tabela_temporaria_de_solicitacao extends CI_Migration {

	public function up() {

		// Creating student_request table
		$this->dbforge->add_field(array(
			'id_student' => array('type' => 'INT'),
			'id_course' => array('type' => 'INT'),
			'id_semester'  => array('type' => 'INT'),
			'discipline_class'  => array('type' => 'INT')
		));
		$this->dbforge->create_table('temporary_student_request', TRUE);

		$student_fk = "ALTER TABLE temporary_student_request ADD CONSTRAINT TEMP_IDSTUDENT_REQUEST_FK FOREIGN KEY (id_student) REFERENCES users(id)";
		$this->db->query($student_fk);

		$course_fk = "ALTER TABLE temporary_student_request ADD CONSTRAINT TEMP_IDCOURSE_REQUEST_FK FOREIGN KEY (id_course) REFERENCES course(id_course)";
		$this->db->query($course_fk);
		
		$semester_fk = "ALTER TABLE temporary_student_request ADD CONSTRAINT TEMP_IDSEMESTER_REQUEST_FK FOREIGN KEY (id_semester) REFERENCES semester(id_semester)";
		$this->db->query($semester_fk);	

		$discipline_class_fk = "ALTER TABLE temporary_student_request ADD CONSTRAINT TEMP_IDOFFERDISCIPLINE_REQUEST_FK FOREIGN KEY (discipline_class) REFERENCES offer_discipline(id_offer_discipline)";
		$this->db->query($discipline_class_fk);
	}

	public function down() {

		$student_fk = "ALTER TABLE temporary_student_request DROP FOREIGN KEY TEMP_IDSTUDENT_REQUEST_FK";
		$this->db->query($student_fk);

		$course_fk = "ALTER TABLE temporary_student_request DROP FOREIGN KEY TEMP_IDCOURSE_REQUEST_FK";
		$this->db->query($course_fk);
		
		$semester_fk = "ALTER TABLE temporary_student_request DROP FOREIGN KEY TEMP_IDSEMESTER_REQUEST_FK";
		$this->db->query($semester_fk);
		
		$discipline_class_fk = "ALTER TABLE temporary_student_request DROP FOREIGN KEY TEMP_IDOFFERDISCIPLINE_REQUEST_FK";
		$this->db->query($discipline_class_fk);

		$this->dbforge->drop_table('temporary_student_request');
	}

}
