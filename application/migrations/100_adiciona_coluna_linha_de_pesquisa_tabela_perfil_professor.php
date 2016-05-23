<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_adiciona_coluna_linha_de_pesquisa_tabela_perfil_professor extends CI_Migration {

    public function up() {

        $this->dbforge->add_column('teacher_profile', array(
            'research_line' => array('type' => "varchar(800)")
        ));

    }

    public function down() {

    }

}