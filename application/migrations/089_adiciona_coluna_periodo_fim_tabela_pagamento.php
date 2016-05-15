<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_adiciona_coluna_periodo_fim_tabela_pagamento extends CI_Migration {

    public function up() {

        $this->dbforge->add_column('payment', array(
            'end_period' => array('type' => "varchar(10)")
        ));

        $this->dbforge->modify_column('payment', array(
            'arrivalInBrazil' => array('type' => "varchar(10)", 'null' => TRUE),
            'installment_date_1' => array('type' => "varchar(10)", 'null' => TRUE),
            'installment_date_2' => array('type' => "varchar(10)", 'null' => TRUE),
            'installment_date_3' => array('type' => "varchar(10)", 'null' => TRUE),
            'installment_date_4' => array('type' => "varchar(10)", 'null' => TRUE),
            'installment_date_5' => array('type' => "varchar(10)", 'null' => TRUE)
        ));
    }

    public function down() {

    }

}