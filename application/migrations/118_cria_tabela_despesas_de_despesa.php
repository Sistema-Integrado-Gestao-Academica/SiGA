<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Cria_tabela_despesas_de_despesa extends CI_Migration {

	public function up() {
		// Expense details table
		$this->dbforge->add_field(array(
			'id' => array('type' => 'INT', 'auto_increment' => true),
			'note' => array('type' => 'VARCHAR(255)', 'NULL' => true),
			'emission_date' => array('type' => 'DATE'),
			'sei_process' => array('type' => 'VARCHAR(20)', 'NULL' => true),
			'value' => array('type' => 'DECIMAL(10,2)'),
			'description' => array('type' => 'VARCHAR(255)', 'NULL' => true),
			'expense_id' => array('type' => 'INT')
		));

		$this->dbforge->add_key('id', true);
		$this->dbforge->create_table('expense_detail', true);

		$this->db->query("ALTER TABLE expense_detail ADD CONSTRAINT ID_EXPENSE_FK FOREIGN KEY (expense_id) REFERENCES expense(id)");

	}

	public function down() {
		$this->dbforge->drop_table('expense_expenses');
	}

}

