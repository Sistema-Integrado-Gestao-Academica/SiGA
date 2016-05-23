<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH."/controllers/Utils.php");

class Migration_Cria_tabela_area_avaliacao extends CI_Migration {

	public function up() {

		$this->dbforge->add_field(array(
				'id_program_area' => array('type' => 'INT','auto_increment' => true),
				'area_name' => array('type' => 'varchar(100)')
		));
		
		$this->dbforge->add_key('id_program_area', TRUE);
		$this->dbforge->create_table('program_area', TRUE);
		
		
		$addColumn = "ALTER TABLE program ADD id_area INT NULL DEFAULT NULL , ADD INDEX (id_area)";
		$this->db->query($addColumn);
		$addConstraint = "ALTER TABLE program ADD CONSTRAINT PROGRAM_AREAID_FK FOREIGN KEY (id_area) REFERENCES program_area(id_program_area) ON DELETE RESTRICT ON UPDATE RESTRICT";
		$this->db->query($addConstraint);
		
		$populate = new Utils();
		$populate->loadAvaliationAreas();
	}

	public function down(){

		$dropConstraint = "ALTER TABLE program DROP FOREIGN KEY PROGRAM_AREAID_FK";
		$this->db->query($dropConstraint);
		
		$dropConstraint = "ALTER TABLE program DROP id_area";
		$this->db->query($dropConstraint);
		
		$this->dbforge->drop_table('program_area');
				
	}
}
