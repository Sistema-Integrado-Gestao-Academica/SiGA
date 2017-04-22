<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Adiciona_coluna_totally_required_process_docs extends CI_Migration {

    public function up() {
        $this->dbforge->add_column('selection_process_available_docs', [
            'totally_required' => ['type' => 'tinyint(1)', 'default' => TRUE]
        ]);
        $this->changeExistingDocs();
    }

    public function down(){
        $this->dbforge->drop_column('selection_process_available_docs', "totally_required");
    }

    private function changeExistingDocs(){
        // Certificado de reservista
        $this->db->where('id', 8);
        // Identidade de estrangeiro
        $this->db->or_where('id', 9);
        $this->db->update('selection_process_available_docs', [
            'totally_required' => FALSE
        ]);
    }
}