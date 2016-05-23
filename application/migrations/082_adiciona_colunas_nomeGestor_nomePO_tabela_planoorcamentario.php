<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_adiciona_colunas_nomeGestor_nomePO_tabela_planoorcamentario extends CI_Migration {

	public function up() {

		$this->dbforge->add_column('budgetplan', array(
			'budgetplan_name' => array('type' => "varchar(20)", "null" => TRUE),
			'manager' => array('type' => 'INT', "null" => TRUE)
		));

		$fk = "ALTER TABLE budgetplan ADD CONSTRAINT MANAGER_BUDGETPLAN FOREIGN KEY (manager) REFERENCES users(id)";
		$this->db->query($fk);
	}

	public function down() {

		$this->dbforge->drop_column('budgetplan', 'bugetplan_name');
		$this->dbforge->drop_column('budgetplan', 'manager');
	}
}
