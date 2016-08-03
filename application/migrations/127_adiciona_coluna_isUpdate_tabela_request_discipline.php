<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Adiciona_coluna_isUpdate_tabela_request_discipline extends CI_Migration {

    public function up() {

        $this->dbforge->add_column('request_discipline', array(
            'is_update' => array('type' => 'TINYINT(1)', 'default' => '0')
        ));

        // Create timestamp column on request_discipline
        $this->db->query("ALTER TABLE request_discipline ADD requested_on TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP");
    }

    public function down(){
        $this->dbforge->drop_column('request_discipline', "is_update");
        $this->dbforge->drop_column('request_discipline', "requested_on");
    }
}