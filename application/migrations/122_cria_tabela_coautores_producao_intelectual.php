<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_cria_tabela_coautores_producao_intelectual extends CI_Migration {

	public function up() {
		
		// Coauthor table
		$this->dbforge->add_field(array(
			'author_name' => array('type' => 'VARCHAR(100)'),
			'cpf' => array('type' => 'VARCHAR(11)', 'NULL' => true),
			'user_id' => array('type' => 'INT', 'NULL' => true),
			'production_id' => array('type' => 'INT')
		));

		$this->dbforge->create_table('production_coauthor', true);

		$this->db->query("ALTER TABLE production_coauthor ADD CONSTRAINT ID_PRODUCTION_COAUTHOR_FK FOREIGN KEY (production_id) REFERENCES intellectual_production(id)");

		$this->db->query("ALTER TABLE production_coauthor ADD CONSTRAINT ID_USER_COAUTHOR_FK FOREIGN KEY (user_id) REFERENCES users(id)");
	}

	public function down() {
		
	}

}