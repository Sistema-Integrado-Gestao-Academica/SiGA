<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Cria_tabela_solicitacao extends CI_Migration {

	public function up() {

		// Creating student_request table
		$this->dbforge->add_field(array(
			'id_request' => array('type' => 'INT', 'auto_increment' => TRUE),
			'id_student' => array('type' => 'INT'),
			'id_course' => array('type' => 'INT'),
			'id_semester'  => array('type' => 'INT'),
			'request_status' => array('type' => 'varchar(15)')
		));
		$this->dbforge->add_key('id_request', TRUE);
		$this->dbforge->create_table('student_request', TRUE);

		$student_fk = "ALTER TABLE student_request ADD CONSTRAINT IDSTUDENT_REQUEST_FK FOREIGN KEY (id_student) REFERENCES users(id)";
		$this->db->query($student_fk);

		$course_fk = "ALTER TABLE student_request ADD CONSTRAINT IDCOURSE_REQUEST_FK FOREIGN KEY (id_course) REFERENCES course(id_course)";
		$this->db->query($course_fk);
		
		$semester_fk = "ALTER TABLE student_request ADD CONSTRAINT IDSEMESTER_REQUEST_FK FOREIGN KEY (id_semester) REFERENCES semester(id_semester)";
		$this->db->query($semester_fk);

		// Creating relation table of request and discipline
		$this->dbforge->add_field(array(
			'id_request' => array('type' => 'INT'),
			'id_offer'  => array('type' => 'INT'),
			'id_discipline'  => array('type' => 'INT'),
			'discipline_class'  => array('type' => 'varchar(3)')
		));
		$this->dbforge->create_table('request_discipline', TRUE);

		$class_uk = "ALTER TABLE request_discipline ADD CONSTRAINT CLASS_REQUEST_UK UNIQUE (id_offer, id_discipline, discipline_class)";
		$this->db->query($class_uk);

		$fk = "ALTER TABLE request_discipline ADD CONSTRAINT IDREQUEST_FK FOREIGN KEY (id_request) REFERENCES student_request(id_request)";
		$this->db->query($fk);

		$offer_fk = "ALTER TABLE request_discipline ADD CONSTRAINT OFFER_REQUEST_FK FOREIGN KEY (id_offer) REFERENCES offer(id_offer)";
		$this->db->query($offer_fk);

		$discipline_fk = "ALTER TABLE request_discipline ADD CONSTRAINT DISCIPLINE_REQUEST_FK FOREIGN KEY (id_discipline) REFERENCES discipline(discipline_code)";
		$this->db->query($discipline_fk);
	}

	public function down() {

		$fk = "ALTER TABLE request_discipline DROP FOREIGN KEY IDREQUEST_FK";
		$this->db->query($fk);

		$offer_fk = "ALTER TABLE request_discipline DROP FOREIGN KEY OFFER_REQUEST_FK";
		$this->db->query($offer_fk);

		$discipline_fk = "ALTER TABLE request_discipline DROP FOREIGN KEY DISCIPLINE_REQUEST_FK";
		$this->db->query($discipline_fk);

		$this->dbforge->drop_table('request_discipline');


		$student_fk = "ALTER TABLE student_request DROP FOREIGN KEY IDSTUDENT_REQUEST_FK";
		$this->db->query($student_fk);

		$course_fk = "ALTER TABLE student_request DROP FOREIGN KEY IDCOURSE_REQUEST_FK";
		$this->db->query($course_fk);
		
		$semester_fk = "ALTER TABLE student_request DROP FOREIGN KEY IDSEMESTER_REQUEST_FK";
		$this->db->query($semester_fk);

		$this->dbforge->drop_table('student_request');
	}

}
