<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Cria_tabela_programa_base extends CI_Migration {

	public function up() {

        $this->dbforge->add_field(array(
            'id' => array('type' => 'INT', 'auto_increment' => true),
            'name' => array('type' => 'VARCHAR(100)'),
            'start_year' => array('type' => 'VARCHAR(4)'),
            'end_year' => array('type' => 'VARCHAR(4)'),
            'productions' => array('type' => 'INT'),
            'teachers' => array('type' => 'INT'),
            'grade' => array('type' => 'INT'),
        ));

        $this->dbforge->add_key('id', true);
        $this->dbforge->create_table('base_program_evaluation', true);
	}
	public function down(){
	}
}