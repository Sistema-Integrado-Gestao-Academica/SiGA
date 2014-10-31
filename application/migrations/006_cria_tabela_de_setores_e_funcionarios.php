<?php 
class Migration_Cria_tabela_de_setores_e_funcionarios extends CI_migration {
	
	public function up() {
		$this->dbforge->add_field(array(
			'setor_id' => array('type' => 'INT'),
			'funcionarios_id' => array('type' => 'INT')
		));
		$this->dbforge->add_key('setor_id', true);
		$this->dbforge->add_key('funcionarios_id', true);
		$this->dbforge->create_table('setores_funcionarios');
	}

	public function down() {
		$this->dbforge->drop_table('setores_funcionarios');
	}
}
