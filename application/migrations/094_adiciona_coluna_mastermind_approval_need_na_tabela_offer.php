<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Adiciona_coluna_mastermind_approval_need_na_tabela_offer extends CI_Migration {

    public function up() {

        $this->dbforge->add_column('offer', array(

            'needs_mastermind_approval' => array('type' => "tinyint(1)", 'default' => "0")
        ));
    }

    public function down() {

    }
}

?>

