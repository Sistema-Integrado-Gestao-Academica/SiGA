<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Altera_tabela_funcionarios extends CI_Migration {

	public function up() {

		$this->dbforge->drop_table('funcionarios');

		$this->dbforge->add_field(array(
			'id_staff' => array('type' => 'INT','auto_increment' => true),
			'id_user' => array('type' => 'INT'),
			'pisPasep' => array('type' => 'INT',  'null' => FALSE),
			'registration' => array('type' => 'INT(10)',  'null' => TRUE),
			'brazil_landing' => array('type' => 'varchar(10)',  'null' => TRUE),
			'address' => array('type' => 'varchar(50)'),
			'telephone' => array('type' => 'varchar(15)'),
			'bank' => array('type' => 'varchar(25)', 'null' => TRUE),
			'agency' => array('type' => 'varchar(10)', 'null' => TRUE),
			'checking_account' => array('type' => 'varchar(15)','null' => TRUE)
		));

		$this->dbforge->add_key('id_staff', true);
		$this->dbforge->create_table('staffs', true);

		$addConstraint = "ALTER TABLE staffs ADD CONSTRAINT IDUSER_STAFF_FK FOREIGN KEY (id_user) REFERENCES users(id) ON DELETE CASCADE ON UPDATE RESTRICT";
		$this->db->query($addConstraint);
		

		/**
		 *	Updating staffs rote in permissions table
		 */

		$this->db->where('id_permission', 2);
		$this->db->update('permission', array('route' => "staffs"));

	}

	public function down(){
		
		$this->dbforge->add_field(array(
			'id' => array('type' => 'INT','auto_increment' => true),
			'nome' => array('type' => 'varchar(255)')
		));
		$this->dbforge->add_key('id', true);
		$this->dbforge->create_table('funcionarios', true);

		$dropConstraint = "ALTER TABLE staffs DROP FOREIGN KEY IDUSER_STAFF_FK";
		$this->db->query($dropConstraint);
		
		$this->dbforge->drop_table('staffs');
		
	}
}
