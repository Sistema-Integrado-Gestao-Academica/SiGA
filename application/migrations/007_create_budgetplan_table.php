<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Create_budgetplan_table extends CI_Migration {

	public function up() {
		// Budget plan table
		$this->dbforge->add_field(array(
			'id' => array('type' => 'INT', 'auto_increment' => true),
			'amount' => array('type' => 'DECIMAL(10,2)', 'NULL' => true),
			'spending' => array('type' => 'DECIMAL(10,2)', 'NULL' => true, 'default' => 0),
			'balance' => array('type' => 'DECIMAL(10,2)', 'NULL' => true),
			'status' => array('type' => 'INT', 'default' => 1),
			'course_id' => array('type' => 'INT', 'NULL' => true)
		));
		$this->dbforge->add_key('id', true);
		$this->dbforge->add_key('status');
		$this->dbforge->add_key('course_id');
		$this->dbforge->create_table('budgetplan', true);

		// Budget plan status table
		$this->dbforge->add_field(array(
			'id' => array('type' => 'INT','auto_increment' => true),
			'description' => array('type' => 'varchar(30)')
		));
		$this->dbforge->add_key('id', true);
		$this->dbforge->create_table('budgetplan_status', true);

		// Adding values of status of budget plan
		$object = array('id' => 1, 'description' => 'Proposta');
		$this->db->insert('budgetplan_status', $object);
		$object = array('id' => 2, 'description' => 'Aprovado');
		$this->db->insert('budgetplan_status', $object);
		$object = array('id' => 3, 'description' => 'Em execução');
		$this->db->insert('budgetplan_status', $object);

		// Adding 'plano orçamentário' permission
		$object = array('id_permission' => 9, 'permission_name' => 'plano orcamentario');
		$this->db->insert('permission', $object);

		$object = array('id_module' => 1, 'id_permission' => 9);
		$this->db->insert('module_permission', $object);
		$object = array('id_module' => 3, 'id_permission' => 9);
		$this->db->insert('module_permission', $object);
	}

	public function down() {
		$this->dbforge->drop_table('budgetplan');
		$this->dbforge->drop_table('budgetplan_status');

		$object = array('id_module' => 1, 'id_permission' => 9);
		$this->db->delete('module_permission', $object);
		$object = array('id_module' => 3, 'id_permission' => 9);
		$this->db->delete('module_permission', $object);

		$object = array('permission_name' => 'plano orcamentario');
		$this->db->delete('permission', $object);
	}
}

/* End of file 007_create_budgetplan_table.php */
/* Location: ./application/migrations/007_create_budgetplan_table.php */ ?>
