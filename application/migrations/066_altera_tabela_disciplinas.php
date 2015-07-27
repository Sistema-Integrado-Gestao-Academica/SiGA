<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Altera_tabela_disciplinas extends CI_Migration {

	public function up() {

		$addConstraint = "ALTER TABLE discipline ADD id_course_discipline INT AFTER workload, ADD INDEX (id_course_discipline) ";
		$this->db->query($addConstraint);
		
		$addConstraint = "ALTER TABLE discipline ADD FOREIGN KEY (id_course_discipline) REFERENCES course(id_course) ON DELETE CASCADE ON UPDATE RESTRICT;";
		$this->db->query($addConstraint);
	}

	public function down(){
		
		$this->dbforge->drop_table('mastermind_message');
		
		$dropConstraint = "ALTER TABLE discipline DROP FOREIGN KEY IDCOURSEDISCIPLINE_FK";
		$this->db->query($dropConstraint);
		
		$dropConstraint = "ALTER TABLE discipline DROP column id_course_discipline";
		$this->db->query($dropConstraint);
		
	}
}
