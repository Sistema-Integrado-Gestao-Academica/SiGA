<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Cria_tabela_relacao_curriculo_disciplina extends CI_Migration {

	public function up() {

		// Course Syllabus and discipline relation table
		$this->dbforge->add_field(array(
			'id_syllabus' => array('type' => 'INT'),
			'id_discipline' => array('type' => 'INT')
		));
		$this->dbforge->create_table('syllabus_discipline', true);

		$id_syllabus_fk = "ALTER TABLE syllabus_discipline ADD CONSTRAINT IDSYLLABUS_SYLLABUSDISCIPLINE_FK FOREIGN KEY (id_syllabus) REFERENCES course_syllabus(id_syllabus)";
		$this->db->query($id_syllabus_fk);

		$id_discipline_fk = "ALTER TABLE syllabus_discipline ADD CONSTRAINT IDDISCIPLINE_SYLLABUSDISCIPLINE_FK FOREIGN KEY (id_discipline) REFERENCES discipline(discipline_code)";
		$this->db->query($id_discipline_fk);

	}

	public function down() {
		
		$id_syllabus_fk = "ALTER TABLE syllabus_discipline DROP CONSTRAINT IDSYLLABUS_SYLLABUSDISCIPLINE_FK";
		$this->db->query($id_syllabus_fk);

		$id_discipline_fk = "ALTER TABLE syllabus_discipline DROP CONSTRAINT IDDISCIPLINE_SYLLABUSDISCIPLINE_FK";
		$this->db->query($id_discipline_fk);

		$this->dbforge->drop_table('syllabus_discipline');
	}

}
