<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Cria_tabela_despesas_de_plano_orcamentario extends CI_Migration {

	public function up() {
		// Expense table
		$this->dbforge->add_field(array(
			'id' => array('type' => 'INT', 'auto_increment' => true),
			'year' => array('type' => 'INT', 'NULL' => true),
			'nature' => array('type' => 'VARCHAR(255)', 'NULL' => true),
			'month' => array('type' => 'VARCHAR(20)', 'NULL' => true),
			'value' => array('type' => 'DECIMAL(10,2)'),
			'budgetplan_id' => array('type' => 'INT')
		));

		$this->dbforge->add_key('id', true);
		$this->dbforge->create_table('expense', true);

		$this->db->query("ALTER TABLE expense ADD CONSTRAINT IDBUDGETPLAN_EXPENSE_FK FOREIGN KEY (budgetplan_id) REFERENCES budgetplan(id)");

		// modify "plano orcamentario" route
		$this->db->where('permission_name', 'Plano Orcamentário');
		$object = array('permission_name' => 'Plano Orçamentário', 'route' => 'planoorcamentario');
		$this->db->update('permission', $object);
	}

	public function down() {
		$this->dbforge->drop_table('expense');
	}

}

/* End of file 020_cria_tabela_despesas_de_plano_orcamentario.php */
/* Location: ./application/migrations/020_cria_tabela_despesas_de_plano_orcamentario.php */ ?>