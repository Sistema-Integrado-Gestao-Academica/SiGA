<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Cria_tabela_de_semestre extends CI_Migration {

	public function up() {
		// Current semester table
		$this->dbforge->add_field(array(
			'id' => array('type' => 'INT')
		));

		$this->dbforge->create_table('current_semester', true);

		// Inserting data
		$object = array('id' => 2);
		$this->db->insert('current_semester', $object);

		// Semester table
		$this->dbforge->add_field(array(
			'id' => array('type' => 'INT', 'auto_increment' => true),
			'description' => array('type' => 'varchar(7)')
		));

		$this->dbforge->add_key('id', true);
		$this->dbforge->create_table('semester', true);

		// Inserting data
		for ($year=2014; $year < 2214; $year++) {
			$object = array('description' => '1º/'.(string)$year);
			$this->db->insert('semester', $object);
			$object = array('description' => '2º/'.(string)$year);
			$this->db->insert('semester', $object);
		}

	}

	public function down() {
		$this->dbforge->drop_table('semester');
		$this->dbforge->drop_table('current_semester');
	}

}

/* End of file 025_cria_tabela_de_semestre.php */
/* Location: ./application/migrations/025_cria_tabela_de_semestre.php */ ?>