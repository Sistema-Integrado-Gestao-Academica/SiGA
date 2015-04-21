<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Cria_tabela_relacao_entre_orientador_estudante extends CI_Migration {

	public function up() {
		// Creating class_hour table
		$this->dbforge->add_field(array(
				'id_mastermind' => array('type' => 'INT'),
				'id_student' => array('type' => 'INT')
		));
		$this->dbforge->add_key('id_mastermind', TRUE);
		$this->dbforge->add_key('id_student', TRUE);
		$this->dbforge->create_table('mastermind_student', TRUE);
		
	}

	public function down(){
		
		$this->dbforge->drop_table('mastermind_student');
		
	}
}