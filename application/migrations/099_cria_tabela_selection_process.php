<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(MODULESPATH."program/constants/SelectionProcessConstants.php");

class Migration_cria_tabela_selection_process extends CI_Migration {

	public function up() {

		// Creating selection process table
		$this->dbforge->add_field(array(
			'id_process' => array('type' => 'INT', 'auto_increment' => true),
			'id_course' => array('type' => "INT"),
			'process_type' => array('type' => 'VARCHAR(20)'),
			'notice_name' => array('type' => 'VARCHAR(60)'),
			'notice_path' => array('type' => 'TEXT', "null" => TRUE),
			'start_date' => array('type' => 'DATE'),
			'end_date' => array('type' => 'DATE'),
			'phase_order' => array('type' => "VARCHAR(200)", 'null' => TRUE)
		));
		$this->dbforge->add_key('id_process', true);
		$this->dbforge->create_table('selection_process', TRUE);
		
		// Adding course FK
		$addConstraint = "ALTER TABLE selection_process ADD CONSTRAINT SELECTION_PROCESS_COURSE_FK FOREIGN KEY (id_course) REFERENCES course(id_course) ON DELETE NO ACTION ON UPDATE RESTRICT";
		$this->db->query($addConstraint);

		// Adding notice_name Unique constraint
		$ukConstraint = "ALTER TABLE selection_process ADD CONSTRAINT NOTICE_NAME_UK UNIQUE(notice_name)";
		$this->db->query($ukConstraint);

		// Creating Selection Process permission
		$this->db->insert('permission', array('permission_name' => "Processo Seletivo", 'route' => "selection_process", "id_permission"=>29));
		
		// Adding permission to coordinator, academic secretary and admin
		$this->db->insert('group_permission', array('id_group' => 9, 'id_permission' => 29));
		$this->db->insert('group_permission', array('id_group' => 11,'id_permission' => 29));
		$this->db->insert('group_permission', array('id_group' => 3,'id_permission' => 29));
		
		// Creating phase table
		$this->dbforge->add_field(array(
			'id_phase' => array('type' => 'INT', 'auto_increment' => true),
			'phase_name' => array('type' => "VARCHAR(30)"),
			'default_weight' => array('type' => 'INT'),
		));
		$this->dbforge->add_key('id_phase', true);
		$this->dbforge->create_table('phase', TRUE);

		// Creating ProcessPhase table
		$this->dbforge->add_field(array(
			'id_process' => array('type' => 'INT'),
			'id_phase' => array('type' => "INT"),
			'weight' => array('type' => 'INT'),
		));
		$this->dbforge->create_table('process_phase', TRUE);

		$processConstraint = "ALTER TABLE process_phase ADD CONSTRAINT PROCESS_PHASE_PROCESS_FK FOREIGN KEY (id_process) REFERENCES selection_process(id_process) ON DELETE CASCADE ON UPDATE RESTRICT";
		$this->db->query($processConstraint);

		$phaseConstraint = "ALTER TABLE process_phase ADD CONSTRAINT PROCESS_PHASE_PHASE_FK FOREIGN KEY (id_phase) REFERENCES phase(id_phase) ON DELETE NO ACTION ON UPDATE RESTRICT";
		$this->db->query($phaseConstraint);

		// Populate Phase table with the available phases
		$phases = array(
			SelectionProcessConstants::HOMOLOGATION_PHASE => 0,
			SelectionProcessConstants::PRE_PROJECT_EVALUATION_PHASE => 3,
			SelectionProcessConstants::WRITTEN_TEST_PHASE => 3,
			SelectionProcessConstants::ORAL_TEST_PHASE => 4
		);

		foreach($phases as $phase => $weight){
			
			$this->db->insert(
				"phase",
				array(
					'phase_name' => $phase,
					'default_weight' => $weight
				)
			);
		}

	}

	public function down(){
		
		$dropConstraint = "ALTER TABLE selection_process DROP FOREIGN KEY SELECTION_PROCESS_COURSE_FK";
		$this->db->query($dropConstraint);

		$this->dbforge->drop_table('selection_process');

		$this->dbforge->drop_table('phase');

		$dropConstraint = "ALTER TABLE process_phase DROP FOREIGN KEY PROCESS_PHASE_PROCESS_FK";
		$this->db->query($dropConstraint);

		$dropConstraint = "ALTER TABLE process_phase DROP FOREIGN KEY PROCESS_PHASE_PHASE_FK";
		$this->db->query($dropConstraint);

		$this->dbforge->drop_table('process_phase');
		
	}
}
