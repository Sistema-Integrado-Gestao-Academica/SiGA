<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Cria_tabela_mensagem_orientador_aluno extends CI_Migration {

	public function up() {

		$this->dbforge->add_field(array(
				'id_mastermind' => array('type' => 'INT'),
				'id_student' => array('type' => 'INT'),
				'id_request' => array('type' => 'INT', 'null'=>TRUE),
				'message' => array('type' => 'varchar(500)')
		));
		
		$this->dbforge->create_table('mastermind_message', TRUE);

		// $addConstraint = "ALTER TABLE mastermind_message ADD CONSTRAINT IDMASTERMIND_FK FOREIGN KEY (id_mastermind) REFERENCES mastermind_student(id_mastermind) ON DELETE CASCADE ON UPDATE RESTRICT";
		// $this->db->query($addConstraint);
		
		$addConstraint = "ALTER TABLE mastermind_message ADD CONSTRAINT IDREQUEST_MASTERMIND_FK FOREIGN KEY (id_request) REFERENCES student_request(id_request) ON DELETE CASCADE ON UPDATE RESTRICT";
		$this->db->query($addConstraint);
	}

	public function down(){
		
		$this->dbforge->drop_table('mastermind_message');
		
		$dropConstraint = "ALTER TABLE mastermind_message DROP FOREIGN KEY IDMASTERMIND_FK";
		$this->db->query($dropConstraint);
		
		$dropConstraint = "ALTER TABLE mastermind_message DROP FOREIGN KEY IDREQUEST_FK";
		$this->db->query($dropConstraint);
		
	}
}
