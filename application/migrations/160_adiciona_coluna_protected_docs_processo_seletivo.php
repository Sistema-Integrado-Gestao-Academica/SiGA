<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_adiciona_coluna_protected_docs_processo_seletivo extends CI_Migration {

    public function up() {
        $this->dbforge->add_column('selection_process_available_docs', [
            'protected' => ['type' => 'tinyint(1)', 'default' => TRUE]
        ]);

        $this->dbforge->add_column('selection_process_user_subscription', [
            'homologated' => ['type' => 'tinyint(1)', 'default' => FALSE]
        ]);

        $this->makePreProjectNotProtected();
    }

    public function down() {
        $this->dbforge->drop_column('selection_process_available_docs', 'protected');
        $this->dbforge->drop_column('selection_process_user_subscription', 'homologated');
    }

    private function makePreProjectNotProtected(){
        $this->db->where('id', 10);
        $this->db->update('selection_process_available_docs', [
            'protected' => FALSE
        ]);
    }

}