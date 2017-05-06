<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_adiciona_coluna_vagas_tabela_processo_seletivo extends CI_Migration {

    public function up() {

        $this->dbforge->add_column('selection_process', array(
            'total_vacancies' => array('type' => 'INT', 'default' => 1)
        ));
    }

    public function down() {

    }

}