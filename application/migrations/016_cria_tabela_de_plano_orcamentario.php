<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Cria_tabela_de_plano_orcamentario extends CI_Migration {

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
		$this->dbforge->create_table('budgetplan', true);

		$add_courseid_fk = "ALTER TABLE budgetplan ADD CONSTRAINT IDCOURSE_BUDGETPLAN_FK FOREIGN KEY (course_id) REFERENCES course(id_course)";
		$this->db->query($add_courseid_fk);

		// Budget plan status table
		$this->dbforge->add_field(array(
			'id' => array('type' => 'INT','auto_increment' => true),
			'description' => array('type' => 'varchar(30)')
		));
		$this->dbforge->add_key('id', true);
		$this->dbforge->create_table('budgetplan_status', true);

		// Adding the status as foreign key on the budgetplan table
		$add_courseid_fk = "ALTER TABLE budgetplan ADD CONSTRAINT IDSTATUS_BUDGETPLAN_FK FOREIGN KEY (status) REFERENCES budgetplan_status(id)";
		$this->db->query($add_courseid_fk);

		// Adding values of status of budget plan
		$budgetplan_status = array('Proposta', 'Aprovado', 'Em execução', 'Finalizado');
		foreach ($budgetplan_status as $value) {
			$this->db->insert('budgetplan_status', array('description' => $value));
		}

		// Creating permission for the budgetplan
		$object = array('id_group' => 1, 'id_permission' => 7);
		$this->db->insert('group_permission', $object);
		$object = array('id_group' => 3, 'id_permission' => 7);
		$this->db->insert('group_permission', $object);
	}

	public function down() {
		$this->dbforge->drop_table('budgetplan');
		$this->dbforge->drop_table('budgetplan_status');

		$object = array('id_group' => 1, 'id_permission' => 7);
		$this->db->delete('group_permission', $object);
		$object = array('id_group' => 3, 'id_permission' => 7);
		$this->db->delete('group_permission', $object);
	}
}

/* End of file 014_cria_tabela_de_plano_orcamentario.php */
/* Location: ./application/migrations/014_cria_tabela_de_plano_orcamentario.php */ ?>
