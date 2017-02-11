<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_adiciona_tipos_de_producao extends CI_Migration {

	public function up() {
		
		$this->dbforge->add_field(array(
			'id' => array('type' => 'INT', 'auto_increment' => TRUE),
			'event_name' => array('type' => 'VARCHAR(100)'),
			'event_nature' => array('type' => 'VARCHAR(10)'),
			'place' => array('type' => 'VARCHAR(50)', 'NULL' => TRUE),
			'start_date' => array('type' => 'date', 'NULL' => TRUE),
            'end_date' => array('type' => 'date', 'NULL' => TRUE),
			'promoting_institution' => array('type' => 'VARCHAR(100)', 'NULL' => TRUE),
			'student' => array('type' => 'INT'),
			'study_title' => array('type' => 'VARCHAR(255)', 'NULL' => TRUE),
			'presentation_nature' => array('type' => 'VARCHAR(16)', 'NULL' => TRUE),
		));

		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('student_event_production', TRUE);

		$this->db->query("ALTER TABLE student_event_production ADD CONSTRAINT ID_USER_PRODUCTION_FK FOREIGN KEY (student) REFERENCES users(id)");
		

		// Updating permission name from "Produção Intelectual" to "Produções"
		$this->db->where("route","intellectual_production");
		$this->db->update('permission', array('permission_name' => "Produções"));
	}

	public function down() {
		
	}
}