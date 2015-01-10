<?php
class Migration_Cria_tabelas_de_programas extends CI_migration {

	public function up() {

	// Academic program table
		$this->dbforge->add_field(array(
				'id_academic_program'  => array('type' => 'INT', 'auto_increment' => TRUE),
				'id_course' => array('type' => 'INT'),
				'id_master_degree' => array('type' => 'INT', 'null' => TRUE),
				'id_doctorate' => array('type' => 'INT', 'null' => TRUE)
		));
		$this->dbforge->add_key('id_academic_program', true);
		$this->dbforge->create_table('academic_program', true);

		$add_idcourse_unique_constraint = "ALTER TABLE academic_program ADD CONSTRAINT ID_COURSE_ACADEMICPROG_UK UNIQUE (id_course)";
		$this->db->query($add_idcourse_unique_constraint);

		$add_idcourse_fk = "ALTER TABLE academic_program ADD CONSTRAINT IDCOURSE_ACADEMICPROG_FK FOREIGN KEY (id_course) REFERENCES course(id_course) ON DELETE CASCADE ON UPDATE RESTRICT";
		$this->db->query($add_idcourse_fk);

		$add_idmasterdegree_fk = "ALTER TABLE academic_program ADD CONSTRAINT IDMASTERDEG_ACADEMICPROG_FK FOREIGN KEY (id_master_degree) REFERENCES master_degree(id_master_degree) ON DELETE SET NULL ON UPDATE RESTRICT";
		$this->db->query($add_idmasterdegree_fk);

		$add_iddoctorate_fk = "ALTER TABLE academic_program ADD CONSTRAINT IDDOCTORATE_ACADEMICPROG_FK FOREIGN KEY (id_doctorate) REFERENCES doctorate(id_doctorate) ON DELETE SET NULL ON UPDATE RESTRICT";
		$this->db->query($add_iddoctorate_fk);
	// End of academic program table
	
	// Professional program table
		$this->dbforge->add_field(array(
				'id_professional_program'  => array('type' => 'INT', 'auto_increment' => TRUE),
				'id_course' => array('type' => 'INT'),
				'id_master_degree' => array('type' => 'INT', 'null' => TRUE)
		));
		$this->dbforge->add_key('id_professional_program', true);
		$this->dbforge->create_table('professional_program', true);

		$add_idcourse_unique_constraint = "ALTER TABLE professional_program ADD CONSTRAINT ID_COURSE_PROFPROG_UK UNIQUE (id_course)";
		$this->db->query($add_idcourse_unique_constraint);

		$add_idcourse_fk = "ALTER TABLE professional_program ADD CONSTRAINT IDCOURSE_PROFPROG_FK FOREIGN KEY (id_course) REFERENCES course(id_course) ON DELETE CASCADE ON UPDATE RESTRICT";
		$this->db->query($add_idcourse_fk);

		$add_idmasterdegree_fk = "ALTER TABLE professional_program ADD CONSTRAINT IDMASTERDEG_PROFPROG_FK FOREIGN KEY (id_master_degree) REFERENCES master_degree(id_master_degree) ON DELETE SET NULL ON UPDATE RESTRICT";
		$this->db->query($add_idmasterdegree_fk);

	// End of Professional program table


	}

	public function down() {
		$this->dbforge->drop_table('academic_program');
		$this->dbforge->drop_table('professional_program');
	}
}
