<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_adiciona_colunas_aprovacao_orientador_e_secretario_na_tabela_student_request extends CI_Migration {

	public function up() {

		$this->dbforge->add_column('student_request', array(
			'mastermind_approval' => array('type' => "INT", "default" => 0),
			'secretary_approval' => array('type' => "INT", "default" => 0)
		));		
	}

	public function down() {
		
		$this->dbforge->drop_column('student_request', 'mastermind_approval');
		$this->dbforge->drop_column('student_request', 'secretary_approval');
	}

}