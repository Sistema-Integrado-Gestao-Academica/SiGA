<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Adiciona_coluna_projeto_na_producao_intelectual extends CI_Migration {

	public function up() {

		$this->dbforge->add_column('intellectual_production', array(
			'project' => array('type' => 'INT', 'null' => TRUE)
		));

        $this->db->query("ALTER TABLE intellectual_production ADD CONSTRAINT ID_PROJECT_PRODUCTION_FK FOREIGN KEY (project) REFERENCES academic_project(id)");

	}

	public function down(){
		$this->dbforge->add_column('intellectual_production', "project");
	}
}