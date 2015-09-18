<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Cria_tabela_avaliacao_capes extends CI_Migration {

	public function up() {

		$this->dbforge->add_field(array(
				'id_avaliation' => array('type' => 'INT'),
				'id_course' => array('type' => 'INT'),
				'course_grade' => array('type' => 'INT'),
				'visualized' => array('type' => 'tinyint(1)',
									  'default' => '0')
		));
		
		$this->dbforge->add_key('id_avaliation', TRUE);
		// $this->dbforge->add_key('id_course', TRUE);
		$this->dbforge->create_table('capes_avaliation', TRUE);
		
		$addConstraint = "ALTER TABLE capes_avaliation ADD CONSTRAINT CAPES_COURSEID_FK FOREIGN KEY (id_course) REFERENCES course(id_course) ON DELETE CASCADE ON UPDATE RESTRICT";
		$this->db->query($addConstraint);
		
	}

	public function down(){
		
		$dropConstraint = "ALTER TABLE capes_avaliation DROP FOREIGN KEY CAPES_COURSEID_FK";
		$this->db->query($dropConstraint);

		$this->dbforge->drop_table('capes_avaliation');		
	}
}
