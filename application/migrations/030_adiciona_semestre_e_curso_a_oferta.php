<?php
class Migration_Adiciona_semestre_e_curso_a_oferta extends CI_migration {

	public function up() {
		
		// Dropping the offer column
		$offer_fk = "ALTER TABLE semester DROP FOREIGN KEY IDOFFER_SEMESTER_FK";
		$this->db->query($offer_fk);
		$this->dbforge->drop_column('semester', 'offer');

		// Add the semester and the course to the offer table
		$columns = array(
			'semester' => array('type' => 'INT'),
			'course' => array('type' => 'INT')
		);
		$this->dbforge->add_column('offer', $columns);

		$course_fk = "ALTER TABLE offer ADD CONSTRAINT IDCOURSE_OFFER_FK FOREIGN KEY (course) REFERENCES course (id_course) ON DELETE NO ACTION";
		$this->db->query($course_fk);

		$semester_fk = "ALTER TABLE offer ADD CONSTRAINT IDSEMESTER_OFFER_FK FOREIGN KEY (semester) REFERENCES semester (id_semester) ON DELETE NO ACTION";
		$this->db->query($semester_fk);

		$semester_course_uk = "ALTER TABLE offer ADD CONSTRAINT SEMESTER_COURSE_UK UNIQUE (semester, course)";
		$this->db->query($semester_course_uk);

	}

	public function down() {

		$course_fk = "ALTER TABLE offer DROP FOREIGN KEY IDCOURSE_OFFER_FK";
		$this->db->query($course_fk);

		$semester_fk = "ALTER TABLE offer DROP FOREIGN KEY IDSEMESTER_OFFER_FK";
		$this->db->query($semester_fk);

		$semester_course_uk = "ALTER TABLE offer DROP INDEX SEMESTER_COURSE_UK";
		$this->db->query($semester_course_uk);

		$this->dbforge->drop_column('offer', 'semester');
		$this->dbforge->drop_column('offer', 'course');

		$columns = array(
			'offer' => array('type' => 'INT', 'null' => TRUE)
		);
		$this->dbforge->add_column('semester', $columns);

		$offer_fk = "ALTER TABLE semester ADD CONSTRAINT IDOFFER_SEMESTER_FK FOREIGN KEY (offer) REFERENCES offer (id_offer) ON DELETE SET NULL";
		$this->db->query($offer_fk);
	}
}
