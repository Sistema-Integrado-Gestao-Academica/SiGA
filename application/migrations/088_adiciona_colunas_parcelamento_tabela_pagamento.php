<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_adiciona_colunas_parcelamento_tabela_pagamento extends CI_Migration {

	public function up() {

		$this->dbforge->add_column('payment', array(

			'installment_date_1' => array('type' => "date", 'null' => TRUE),
			'installment_date_2' => array('type' => "date", 'null' => TRUE),
			'installment_date_3' => array('type' => "date", 'null' => TRUE),
			'installment_date_4' => array('type' => "date", 'null' => TRUE),
			'installment_date_5' => array('type' => "date", 'null' => TRUE),

			'installment_value_1' => array('type' => "decimal(10,2)", 'null' => TRUE),
			'installment_value_2' => array('type' => "decimal(10,2)", 'null' => TRUE),
			'installment_value_3' => array('type' => "decimal(10,2)", 'null' => TRUE),
			'installment_value_4' => array('type' => "decimal(10,2)", 'null' => TRUE),
			'installment_value_5' => array('type' => "decimal(10,2)", 'null' => TRUE),

			'installment_hour_1' => array('type' => "int", 'null' => TRUE),
			'installment_hour_2' => array('type' => "int", 'null' => TRUE),
			'installment_hour_3' => array('type' => "int", 'null' => TRUE),
			'installment_hour_4' => array('type' => "int", 'null' => TRUE),
			'installment_hour_5' => array('type' => "int", 'null' => TRUE)
		));	
	}

	public function down() {
		
	}

}