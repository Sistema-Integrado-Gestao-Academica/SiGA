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

        // Test data
        $this->db->insert_batch('base_program_evaluation', array(
            array(
                'name' => "Programa Linguistica UFSC",
                'start_year' => "2010",
                'end_year' => "2012",
                'productions' => "698",
                'teachers' => "31",
                'grade' => "6",
            ),
            array(
                'name' => "Programa Linguistica UNICAMP",
                'start_year' => "2010",
                'end_year' => "2012",
                'productions' => "1296",
                'teachers' => "30",
                'grade' => "7",
            ),
        ));
	}

	public function down(){
	}
}