<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Cria_tabela_pagamento extends CI_Migration {

	public function up() {

		$this->dbforge->add_field(array(
				'id_payment' => array('type' => 'INT'),
				'id_expense' => array('type' => 'INT')
		));
		
		$this->dbforge->add_key('id_payment', TRUE);
		$this->dbforge->create_table('payment', TRUE);
		
		$addConstraint = "ALTER TABLE payment ADD CONSTRAINT EXPENSEID_PAYMENT_FK FOREIGN KEY (id_expense) REFERENCES expense(id)";
		$this->db->query($addConstraint);
	}

	public function down(){
		
		$dropConstraint = "ALTER TABLE capes_avaliation DROP FOREIGN KEY EXPENSEID_PAYMENT_FK";
		$this->db->query($dropConstraint);
		
		$this->dbforge->drop_table('payment');
	}
}
