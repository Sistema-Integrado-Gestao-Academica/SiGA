<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_adiciona_coluna_contato_tabela_program extends CI_Migration {

    public function up() {
 

        $this->dbforge->add_column('program', array(

            'contact' => array('type' => "text"),
            'history' => array('type' => "text"),
            'summary' => array('type' => "text"),
            'research_line' => array('type' => "text"),
        )); 


    }

    public function down() {

    }

}