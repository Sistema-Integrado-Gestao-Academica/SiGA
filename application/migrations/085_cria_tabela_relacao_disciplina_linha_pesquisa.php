<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_cria_tabela_relacao_disciplina_linha_pesquisa extends CI_Migration {

	public function up() {

		$this->dbforge->add_field(array(
			'id_discipline' => array('type' => 'INT'),
			'id_research_line' => array('type' => 'INT')
		));
		
		$this->dbforge->add_key('id_discipline', true);
		$this->dbforge->create_table('dicipline_research_line', TRUE);
		
		$addConstraint = "ALTER TABLE dicipline_research_line ADD CONSTRAINT DISCIPLINE_RESEARCH_LINE_FK FOREIGN KEY (id_research_line) REFERENCES research_lines(id_research_line) ON DELETE CASCADE ON UPDATE RESTRICT";
		$this->db->query($addConstraint);

	}

	public function down(){
		
		$dropConstraint = "ALTER TABLE dicipline_research_line DROP FOREIGN KEY DISCIPLINE_RESEARCH_LINE_FK";
		$this->db->query($dropConstraint);

		$this->dbforge->drop_table('dicipline_research_line');
		
	}
}

