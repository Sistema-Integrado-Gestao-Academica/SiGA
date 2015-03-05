<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Arruma_tabelas_solicitacao_e_offer_discipline extends CI_Migration {

	public function up() {

		// Erase all data from table
		$this->db->truncate('offer_discipline');

		// Addind the id_offer_discipline column as primary key
		$column = array(
			'id_offer_discipline' => array('type' => 'INT')
		);
		$this->dbforge->add_column('offer_discipline', $column);

		$pk = "ALTER TABLE offer_discipline ADD PRIMARY KEY (id_offer_discipline)";
		$this->db->query($pk);

		// Adding auto_increment to the primary key
		$column_ai = array(
			'id_offer_discipline' => array('type' => 'INT', 'auto_increment' => TRUE)
		);
		$this->dbforge->modify_column('offer_discipline', $column_ai);

		// Adding UNIQUE constraint on request table
		$uk = "ALTER TABLE student_request ADD CONSTRAINT STUDENT_COURSE_SEMESTER_UK UNIQUE (id_student, id_course, id_semester)";
		$this->db->query($uk);


		// Deleting id_discipline, id_offer and class columns from request_discipline table
		$offer_fk = "ALTER TABLE request_discipline DROP FOREIGN KEY OFFER_REQUEST_FK";
		$this->db->query($offer_fk);
		
		$this->dbforge->drop_column('request_discipline', 'id_offer');

		$discipline_fk = "ALTER TABLE request_discipline DROP FOREIGN KEY DISCIPLINE_REQUEST_FK";
		$this->db->query($discipline_fk);

		$this->dbforge->drop_column('request_discipline', 'id_discipline');

		$discipline_uk = "ALTER TABLE request_discipline DROP INDEX CLASS_REQUEST_UK";
		$this->db->query($discipline_uk);

		$this->dbforge->drop_column('request_discipline', 'discipline_class');

		// Addind the id_offer_discipline column as foreign key on request_discipline
		$column = array(
			'discipline_class' => array('type' => 'INT')
		);
		$this->dbforge->add_column('request_discipline', $column);

		$fk = "ALTER TABLE request_discipline ADD CONSTRAINT IDOFFERDISCIPLINE_REQUESTDISCIPLINE FOREIGN KEY (discipline_class) REFERENCES offer_discipline(id_offer_discipline)";
		$this->db->query($fk);
	}

	public function down() {

	}

}
