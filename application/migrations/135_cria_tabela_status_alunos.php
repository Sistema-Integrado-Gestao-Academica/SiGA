<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_cria_tabela_status_alunos extends CI_Migration {

	public function up() {
		
		// Coauthor table
		$this->dbforge->add_field(array(
			'description' => array('type' => 'VARCHAR(50)'),
			'label_type' => array('type' => 'VARCHAR(10)', 'NULL' => true),
			'user_id' => array('type' => 'INT', 'NULL' => true)
		));

		$this->dbforge->create_table('student_status', true);

		$fk = "ALTER TABLE student_status ADD CONSTRAINT ID_USER_STATUS_FK FOREIGN KEY (user_id) REFERENCES users(id)";
		$this->db->query($fk);
	}

	public function down() {
		
	}

}