<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_cria_tabela_perfil_professor extends CI_Migration {

	public function up() {

		$this->dbforge->add_field(array(
			'id_user' => array('type' => 'INT'),
			'summary' => array('type' => 'text'),
			'lattes_link' => array('type' => 'text')
		));
		
		$this->dbforge->create_table('teacher_profile', TRUE);
		
		$addConstraint = "ALTER TABLE teacher_profile ADD CONSTRAINT ID_USER_FK FOREIGN KEY (id_user) 
		REFERENCES users(id)";
		$this->db->query($addConstraint);

	}

	public function down(){
	
	}
}

