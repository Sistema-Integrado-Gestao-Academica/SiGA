<?php
class Migration_Cria_tabelas_de_ead_graduacao extends CI_migration {

	public function up() {


	// Ead table
		$this->dbforge->add_field(array(
			'id_course' => array('type' => 'INT', 'null' => FALSE)
		));
		$this->dbforge->create_table('ead');

		$add_ead_fk = "ALTER TABLE ead ADD CONSTRAINT IDCOURSE_EAD_FK FOREIGN KEY (id_course) REFERENCES course(id_course) ON DELETE CASCADE ON UPDATE RESTRICT";
		$this->db->query($add_ead_fk);
	// End of ead table

	// Graduation table
		$this->dbforge->add_field(array(
			'id_course' => array('type' => 'INT', 'null' => FALSE)
		));
		$this->dbforge->create_table('graduation');

		$add_graduation_fk = "ALTER TABLE graduation ADD CONSTRAINT IDCOURSE_GRAD_FK FOREIGN KEY (id_course) REFERENCES course(id_course) ON DELETE CASCADE ON UPDATE RESTRICT";
		$this->db->query($add_graduation_fk);
	// End of graduation table

	}

	public function down() {
		$this->dbforge->drop_table('ead');
		$this->dbforge->drop_table('graduation');
	}
}
