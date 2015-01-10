<?php
class Migration_Cria_tabela_de_mestrado_doutorado extends CI_migration {

	public function up() {

		$this->dbforge->add_field(array(
				'id_master_degree' => array('type' => 'INT', 'auto_increment' => TRUE),
				'master_degree_name' => array('type' => 'varchar(40)'),
				'duration'  => array('type' => 'INT'),
				'total_credits' => array('type' => 'INT'),
				'workload'  => array('type' => 'INT'),
				'start_class' => array('type' => 'varchar(6)'),
				'description' => array('type' => 'text')
		));
		$this->dbforge->add_key('id_master_degree', true);
		$this->dbforge->create_table('master_degree', true);

		$add_master_degree_unique_constraint = "ALTER TABLE master_degree ADD CONSTRAINT MASTER_DEGREE_NAME_UK UNIQUE (master_degree_name)";
		$this->db->query($add_master_degree_unique_constraint);


		$this->dbforge->add_field(array(
				'id_doctorate' => array('type' => 'INT', 'auto_increment' => TRUE),
				'doctorate_name' => array('type' => 'varchar(40)'),
				'duration'  => array('type' => 'INT'),
				'total_credits' => array('type' => 'INT'),
				'workload'  => array('type' => 'INT'),
				'start_class' => array('type' => 'varchar(6)'),
				'description' => array('type' => 'text')
		));
		$this->dbforge->add_key('id_doctorate', true);
		$this->dbforge->create_table('doctorate', true);

		$add_doctorate_unique_constraint = "ALTER TABLE doctorate ADD CONSTRAINT DOCTORATE_NAME_UK UNIQUE (doctorate_name)";
		$this->db->query($add_doctorate_unique_constraint);

	}

	public function down() {
		$this->dbforge->drop_table('master_degree');
		$this->dbforge->drop_table('doctorate');
	}
}
