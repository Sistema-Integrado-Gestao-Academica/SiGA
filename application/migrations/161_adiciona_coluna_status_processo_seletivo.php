<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_adiciona_coluna_status_processo_seletivo extends CI_Migration {

    public function up() {
        $this->dbforge->add_column('selection_process', [
            'status' => ['type' => 'varchar(40)', 'null' => TRUE]
        ]);

        $this->dbforge->drop_column('selection_process_evaluation', 'approved');

    }

    public function down() {
        $this->dbforge->drop_column('selection_process', 'current_phase');
    }

}