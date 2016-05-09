<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_adiciona_autoincrement_id_pagamento extends CI_Migration {

	public function up() {

		// Changing the size of the document_type column
		$this->dbforge->modify_column('payment', array(
			'id_payment' => array('type' => "INT", 'auto_increment' => TRUE)
		));
	}

	public function down(){
		
	}
}
