<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Adiciona_coluna_current_role extends CI_Migration {

    public function up() {
        $this->dbforge->add_column('student_request', array(
            'current_role' => array('type' => 'varchar(10)')
        ));
    }

    public function down(){
        $this->dbforge->drop_column('student_request', "current_role");
    }
}