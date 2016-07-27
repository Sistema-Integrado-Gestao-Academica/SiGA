<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Adiciona_coluna_restrict_tabela_discipline extends CI_Migration {

    public function up() {

        $this->dbforge->add_column('discipline', array(
            'restrict' => array('type' => 'TINYINT(1)', 'default' => '0')
        ));
    }

    public function down(){
        $this->dbforge->drop_column('discipline', "restrict");
    }
}