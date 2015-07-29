<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Cria_tabela_dados_estudante extends CI_Migration {

	public function up() {

		$this->dbforge->add_field(array(
				'id_user' => array('type' => 'INT'),
				'student_registration' => array('type' => 'INT'),
				'email' => array('type' => 'varchar(100)'),
				'home_phone_number' => array('type' => 'varchar(10)', 'null' =>TRUE),
				'cell_phone_number' => array('type' => 'varchar(10)', 'null' =>TRUE)
		));
		
		$this->dbforge->add_key('id_user', TRUE);
		$this->dbforge->add_key('student_registration', TRUE);
		$this->dbforge->create_table('students_basic_information', TRUE);
		
		$addConstraint = "ALTER TABLE students_basic_information ADD CONSTRAINT STUDENTUSERID_FK FOREIGN KEY (id_user) REFERENCES users(id) ON DELETE CASCADE ON UPDATE RESTRICT";
		$this->db->query($addConstraint);
		
	}

	public function down(){
		
		$this->dbforge->drop_table('students_basic_information');
		
		$dropConstraint = "ALTER TABLE students_basic_information DROP FOREIGN KEY STUDENTUSERID_FK";
		$this->db->query($dropConstraint);
		
	}
}
