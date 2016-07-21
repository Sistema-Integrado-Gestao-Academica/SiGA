<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Adiciona_coluna_programa_no_projeto extends CI_Migration {

	public function up() {

		$this->dbforge->add_column('academic_project', array(
			'program_id' => array('type' => 'INT')
		));

        $this->db->query("ALTER TABLE academic_project ADD CONSTRAINT ID_PROGRAM_PROJECT_FK FOREIGN KEY (program_id) REFERENCES program(id_program)");

	}

	public function down(){
		$this->dbforge->add_column('academic_project', "program_id");
	}
}