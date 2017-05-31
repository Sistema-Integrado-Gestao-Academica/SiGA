<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_adiciona_coluna_protected_docs_documentos_processo_seletivo extends CI_Migration {

    public function up() {
        $this->dbforge->add_column('selection_process_needed_docs', [
            'protected' => ['type' => 'tinyint(1)', 'default' => TRUE]
        ]);

        $this->dbforge->drop_column('selection_process_available_docs', 'protected');
    }

    public function down() {
        $this->dbforge->drop_column('selection_process_needed_docs', 'protected');
        $this->dbforge->add_column('selection_process_available_docs', [
            'protected' => ['type' => 'tinyint(1)', 'default' => TRUE]
        ]);
    }

}