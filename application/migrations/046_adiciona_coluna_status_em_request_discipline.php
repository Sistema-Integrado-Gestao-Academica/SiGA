<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_adiciona_coluna_status_em_request_discipline extends CI_Migration {

	public function up() {

		$this->dbforge->add_column('request_discipline', array(
			'status' => array('type' => 'varchar(15)')
		));

	}

	public function down() {

		$this->dbforge->drop_column('request_discipline', 'status');
	}
}
