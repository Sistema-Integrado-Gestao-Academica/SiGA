<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_adiciona_colunas_flag_tabela_processo_seletivo extends CI_Migration {

    public function up() {

        $this->dbforge->add_column('selection_process', array(
            'dates_defined' => array('tinyint(1)', "default" => 0),
            'needed_docs_selected' => array('tinyint(1)', "default" => 0),
            'teachers_selected' => array('tinyint(1)', "default" => 0)
        ));
    }

    public function down() {

    }

}