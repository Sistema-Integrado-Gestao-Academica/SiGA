<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_arruma_tabela_mastermind_message extends CI_Migration {

	public function up() {

		// Dropping the id_student column
		$this->dbforge->drop_column('mastermind_message', 'id_student');
		
		// Dropping the id_offer_discipline
		$drop_fk = "ALTER TABLE mastermind_message DROP FOREIGN KEY mastermind_message_ibfk_1";
		$this->db->query($drop_fk);
		$this->dbforge->drop_column('mastermind_message', 'id_offer_discipline');

		// Setting the message column to TEXT type
		$this->dbforge->modify_column('mastermind_message', array(
			'message' => array('type' => 'TEXT', 'null' => TRUE)
		));

		//$drop_fk = "ALTER TABLE mastermind_message DROP FOREIGN KEY IDMASTERMIND_FK";
		//$this->db->query($drop_fk);
	}

	public function down() {

	}
}
