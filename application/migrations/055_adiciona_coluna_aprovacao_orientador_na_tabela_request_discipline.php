<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_adiciona_coluna_aprovacao_orientador_na_tabela_request_discipline extends CI_Migration {

	public function up() {

		$this->dbforge->add_column('request_discipline', array(
			'mastermind_approval' => array('type' => "INT", "default" => 0),
			'secretary_approval' => array('type' => "INT", "default" => 0)
		));	
	}

	public function down() {
		
		$this->dbforge->drop_column('request_discipline', 'mastermind_approval');
		$this->dbforge->drop_column('request_discipline', 'secretary_approval');
	}

}