<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Adiciona_coluna_provide_online extends CI_Migration {

    public function up() {

        $this->dbforge->add_column('document_request', array(
            'provide_online' => array('type' => 'tinyint(1)', "default" => 0)
        ));
    }

    public function down(){
    }
}