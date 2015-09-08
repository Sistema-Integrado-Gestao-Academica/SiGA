<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_cria_tabela_relacao_disciplina_linha_pesquisa extends CI_Migration {

	public function up() {

		$this->dbforge->add_field(array(
			'discipline_code' => array('type' => 'INT'),
			'id_research_line' => array('type' => 'INT')
		));
		
		$this->dbforge->create_table('discipline_research_line', TRUE);
		
		$addConstraint = "ALTER TABLE discipline_research_line ADD CONSTRAINT DISCIPLINE_RESEARCH_LINE_FK FOREIGN KEY (id_research_line) REFERENCES research_lines(id_research_line) ON DELETE CASCADE ON UPDATE RESTRICT";
		$this->db->query($addConstraint);

	}

	public function down(){
		
		$dropConstraint = "ALTER TABLE discipline_research_line DROP FOREIGN KEY DISCIPLINE_RESEARCH_LINE_FK";
		$this->db->query($dropConstraint);

		$this->dbforge->drop_table('discipline_research_line');
		
	}
}

