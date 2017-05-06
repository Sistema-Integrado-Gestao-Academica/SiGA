<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_adiciona_coluna_passing_score_processo_seletivo extends CI_Migration {

    public function up() {
        $this->dbforge->add_column('selection_process', [
            'passing_score' => ['type' => 'INT', 'default' => 0]
        ]);

    }

    public function down() {
        $this->dbforge->drop_column('selection_process', 'passing_score');
    }
}