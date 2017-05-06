<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_adiciona_coluna_not_activated_in_users extends CI_Migration {

    public function up() {

        // Creating this column to identify users that registered without confirmating account
        $this->dbforge->add_column('users', [
            'not_activated' => ['type' => 'tinyint(1)', 'default' => FALSE]
        ]);
    }

    public function down() {
        $this->dbforge->drop_column('users', 'not_activated');
    }
}