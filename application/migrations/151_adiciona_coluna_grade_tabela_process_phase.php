<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_adiciona_coluna_grade_tabela_process_phase extends CI_Migration {

    public function up() {

        $this->dbforge->add_column('process_phase', array(
            'grade' => array('type' => "INT")
        ));

        $this->dbforge->modify_column('process_phase', array(
			'start_date' => array('type'=> "DATE", 'null' => TRUE),
			'end_date' => array('type'=> "DATE", 'null' => TRUE)
		));        
    }

    public function down() {

    }

}