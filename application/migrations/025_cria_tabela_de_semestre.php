<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Cria_tabela_de_semestre extends CI_Migration {

	public function up() {
		// Semester table
		$this->dbforge->add_field(array(
			'current' => array('type' => 'VARCHAR(7)')
		));

		$this->dbforge->create_table('semester', true);

		// Inserting data
		$object = array('current' => '2º/2014');
		$this->db->insert('semester', $object);
	}

	public function down() {
		$this->dbforge->drop_table('semester');
	}

}

/* End of file 025_cria_tabela_de_semestre.php */
/* Location: ./application/migrations/025_cria_tabela_de_semestre.php */ ?>