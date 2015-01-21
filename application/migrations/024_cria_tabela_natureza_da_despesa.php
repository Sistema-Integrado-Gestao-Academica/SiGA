<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Cria_tabela_natureza_da_despesa extends CI_Migration {

	public function up() {
		// Expense type table
		$this->dbforge->add_field(array(
			'id' => array('type' => 'INT', 'auto_increment' => true),
			'description' => array('type' => 'VARCHAR(255)')
		));

		$this->dbforge->add_key('id', true);
		$this->dbforge->create_table('expense_type', true);

		// Change "nature" field to "expense_type_id"
		$field = array('nature' => array(
			'name' => 'expense_type_id',
			'type' => 'INT'
		));
		$this->dbforge->modify_column('expense', $field);
		// $this->db->query("ALTER TABLE expense ADD CONSTRAINT IDEXPENSE_TYPE_FK FOREIGN KEY (expense_type_id) REFERENCES expense_type(id)");

		// Inserting data
		$object = array('id' => 339014, 'description' => 'Diárias - Civil');
		$this->db->insert('expense_type', $object);
		$object = array('id' => 339030, 'description' => 'Material de Consumo');
		$this->db->insert('expense_type', $object);
		$object = array('id' => 339033, 'description' => 'Passagens e Despesas com Locomoção');
		$this->db->insert('expense_type', $object);
		$object = array('id' => 339036, 'description' => 'Outros Serviços de Terceiros - Pessoa Física');
		$this->db->insert('expense_type', $object);
		$object = array('id' => 339039, 'description' => 'Outros Serviços de Terceiros - Pessoa Jurídica');
		$this->db->insert('expense_type', $object);
		$object = array('id' => 339147, 'description' => 'Obrigações Tributárias e Contributivas');
		$this->db->insert('expense_type', $object);
		$object = array('id' => 449052, 'description' => 'Equipamentos e Material Permanente');
		$this->db->insert('expense_type', $object);
	}

	public function down() {
		$field = array('expense_type_id' => array(
			'name' => 'nature',
			'type' => 'INT'
		));
		$this->dbforge->modify_column('expense', $field);

		$this->dbforge->drop_table('expense_type');
	}

}

/* End of file 024_cria_tabela_natureza_da_despesa.php */
/* Location: ./application/migrations/024_cria_tabela_natureza_da_despesa.php */