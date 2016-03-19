<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_cria_tabela_selection_process extends CI_Migration {

	public function up() {

		// Creating selection process table
		$this->dbforge->add_field(array(
			'id_process' => array('type' => 'INT', 'auto_increment' => true),
			'noticeName' => array('type' => 'VARCHAR(60)'),
			'notice' => array('type' => 'MEDIUMBLOB'),
			'start_date' => array('type' => 'DATE'),
			'end_date' => array('type' => 'DATE'),
			'phaseOrder' => array('type' => "VARCHAR(200)"),
			'id_course' => array('type' => "INT")
		));
		$this->dbforge->add_key('id_process', true);
		$this->dbforge->create_table('selection_process', TRUE);
		
		$addConstraint = "ALTER TABLE selection_process ADD CONSTRAINT SELECTION_PROCESS_COURSE_FK FOREIGN KEY (id_course) REFERENCES course(id_course) ON DELETE NO ACTION ON UPDATE RESTRICT";
		$this->db->query($addConstraint);

		// Creating Selection Process permission
		$this->db->insert('permission', array('permission_name' => "Processo Seletivo", 'route' => "selection_process", "id_permission"=>28));
		
		// Adding permission to coordinator, academic secretary and admin
		$this->db->insert('group_permission', array('id_group' => 9, 'id_permission' => 28));
		$this->db->insert('group_permission', array('id_group' => 11,'id_permission' => 28));
		$this->db->insert('group_permission', array('id_group' => 3,'id_permission' => 28));
		
	}

	public function down(){
		
		$dropConstraint = "ALTER TABLE selection_process DROP FOREIGN KEY SELECTION_PROCESS_COURSE_FK";
		$this->db->query($dropConstraint);

		$this->dbforge->drop_table('selection_process');
		
	}
}
