<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_adiciona_coluna_type_tabela_fase_processo_seletivo extends CI_Migration {

    public function up() {
        $this->dbforge->add_column('process_phase', [
            'knockout_phase' => ['type' => 'tinyint(1)', 'default' => 0]
        ]);

        $this->dbforge->modify_column('process_phase', array(
            'grade' => array('type' => "INT", 'null' => TRUE)
        ));
    }

    public function down() {
        $this->dbforge->drop_column('process_phase', 'knockout_phase');

        $this->dbforge->modify_column('process_phase', array(
            'grade' => array('type' => "INT")
        ));
    }
}

