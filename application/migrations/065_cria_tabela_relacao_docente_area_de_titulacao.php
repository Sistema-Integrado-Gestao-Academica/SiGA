<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Cria_tabela_relacao_docente_area_de_titulacao extends CI_Migration {

	public function up() {

		// Creating mastermind titling area table
		$this->dbforge->add_field(array(
				'id_mastermind' => array('type' => 'INT'),
				'id_program_area' => array('type' => 'INT'),
				'doctorate_thesis' => array('type' => 'tinyint', 'dafault'=> 0)
		));
		
		$this->dbforge->add_key('id_mastermind', TRUE);
		$this->dbforge->create_table('mastermind_titling_area', TRUE);

		$addConstraint = "ALTER TABLE mastermind_titling_area ADD CONSTRAINT IDTITLINGAREA_FK FOREIGN KEY (id_program_area) REFERENCES program_area(id_program_area) ON DELETE CASCADE ON UPDATE RESTRICT";
		$this->db->query($addConstraint);
		
	}

	public function down(){

		$dropConstraint = "ALTER TABLE mastermind_titling_area DROP FOREIGN KEY IDTITLINGAREA_FK";
		$this->db->query($dropConstraint);
		
		$this->dbforge->drop_table('mastermind_titling_area');

	}
}