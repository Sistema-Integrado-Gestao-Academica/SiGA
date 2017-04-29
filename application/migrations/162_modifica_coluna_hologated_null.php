<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_modifica_coluna_hologated_null extends CI_Migration {

    public function up() {

        $this->dbforge->modify_column('selection_process_user_subscription', [
            'homologated' => ['type' => 'tinyint(1)', 'null' => TRUE, 'default' => NULL]
        ]);

    }

    public function down() {
        $this->dbforge->modify_column('selection_process_user_subscription', [
            'homologated' => ['type' => 'tinyint(1)', 'null' => FALSE, 'default' => FALSE]
        ]);
    }
}