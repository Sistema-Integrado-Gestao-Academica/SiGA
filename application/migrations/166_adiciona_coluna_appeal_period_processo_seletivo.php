<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_adiciona_coluna_appeal_period_processo_seletivo extends CI_Migration {

    public function up() {
        $this->dbforge->add_column('selection_process', [
            'appeal_period' => ['type' => 'tinyint(1)', 'default' => FALSE]
        ]);

    }

    public function down() {
        $this->dbforge->drop_column('selection_process', 'appeal_period');
    }
}