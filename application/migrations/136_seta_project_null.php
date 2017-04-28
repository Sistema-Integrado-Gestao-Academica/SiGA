<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_seta_project_null extends CI_Migration {

	public function up() {

		// Already done in migration 125

		// $this->dbforge->modify_column('intellectual_production', array(
		// 	'project' => array('type'=> "INT", 'null' => TRUE)
		// ));

	 //    $uk = ("ALTER TABLE intellectual_production ADD CONSTRAINT ID_PROJECT_PRODUCTION_FK FOREIGN KEY (project) REFERENCES academic_project(id)");
		// $this->db->query($uk);
	}

	public function down(){

	}
}