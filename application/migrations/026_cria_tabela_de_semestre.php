<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Cria_tabela_de_semestre extends CI_Migration {

	public function up() {
		
		// Semester table
		$this->dbforge->add_field(array(
			'id_semester' => array('type' => 'INT', 'auto_increment' => true),
			'description' => array('type' => 'varchar(7)')
		));

		$this->dbforge->add_key('id_semester', true);
		$this->dbforge->create_table('semester', true);

		// Inserting data
		for ($year=2014; $year < 2214; $year++) {
			$object = array('description' => '1º/'.(string)$year);
			$this->db->insert('semester', $object);
			$object = array('description' => '2º/'.(string)$year);
			$this->db->insert('semester', $object);
		}

		// Current semester table
		$this->dbforge->add_field(array(
			'id_semester' => array('type' => 'INT')
		));
		$this->dbforge->create_table('current_semester', true);

		$id_semester_fk = "ALTER TABLE current_semester ADD CONSTRAINT IDSEMESTER_CURRENTSEM_FK FOREIGN KEY (id_semester) REFERENCES semester(id_semester)";
		$this->db->query($id_semester_fk);

		// Inserting data
		$object = array('id_semester' => 2);
		$this->db->insert('current_semester', $object);
	}

	public function down() {

		$id_semester_fk = "ALTER TABLE current_semester DROP CONSTRAINT IDSEMESTER_CURRENTSEM_FK";
		$this->db->query($id_semester_fk);

		$this->dbforge->drop_table('semester');
		$this->dbforge->drop_table('current_semester');
	}

}

/* End of file 026_cria_tabela_de_semestre.php */
/* Location: ./application/migrations/025_cria_tabela_de_semestre.php */ ?>