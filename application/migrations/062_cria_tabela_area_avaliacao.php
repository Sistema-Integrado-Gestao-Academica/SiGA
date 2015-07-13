<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Cria_tabela_area_avaliacao extends CI_Migration {

	public function up() {

		$this->dbforge->add_field(array(
				'id_avaliation_area' => array('type' => 'INT','auto_increment' => true),
				'area_name' => array('type' => 'varchar(100)')
		));
		
		$this->dbforge->add_key('id_avaliation_area', TRUE);
		$this->dbforge->create_table('capes_avaliation_areas', TRUE);
		
	}

	public function down(){
				
		$this->dbforge->drop_table('capes_avaliation_areas');
				
	}
}
