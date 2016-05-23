<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_aumenta_quantidade_caracteres_request_status extends CI_Migration {

	public function up() {

		$this->dbforge->modify_column('student_request', array(
			'request_status' => array('type' => "varchar(20)")
		));
		
	}

	public function down() {
		
		$this->dbforge->modify_column('student_request', array(
			'request_status' => array('type' => "varchar(15)")
		));
	}

}