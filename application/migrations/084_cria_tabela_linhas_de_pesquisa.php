<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_cria_tabela_linhas_de_pesquisa extends CI_Migration {

	public function up() {

		$this->dbforge->add_field(array(
			'id_research_line' => array('type' => 'INT', 'auto_increment' => true),
			'id_course' => array('type' => 'INT'),
			'description' => array('type' => 'varchar(800)')
		));
		$this->dbforge->add_key('id_research_line', true);
		$this->dbforge->create_table('research_lines', TRUE);
		
		$addConstraint = "ALTER TABLE research_lines ADD CONSTRAINT RESEARCH_LINE_COURSE_FK FOREIGN KEY (id_course) REFERENCES course(id_course) ON DELETE CASCADE ON UPDATE RESTRICT";
		$this->db->query($addConstraint);

	}

	public function down(){
		
		$dropConstraint = "ALTER TABLE research_lines DROP FOREIGN KEY RESEARCH_LINE_COURSE_FK";
		$this->db->query($dropConstraint);

		$this->dbforge->drop_table('research_lines');
		
	}
}
