<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Deleta_tabelas_de_programas_doutorado_mestrado extends CI_Migration {

	public function up() {

		// Dropping existing programs tables
		$this->dbforge->drop_table('academic_program');
		$this->dbforge->drop_table('professional_program');

		// Dropping foreign keys of course_student table to drop the columns of doctorate and master degree
		$fk = "ALTER TABLE course_student DROP FOREIGN KEY IDMASTERDEGREE_COURSESTUDENT_FK";
		$this->db->query($fk);

		$fk = "ALTER TABLE course_student DROP FOREIGN KEY IDDOCTORATE_COURSESTUDENT_FK";
		$this->db->query($fk);

		$fk_index = "ALTER TABLE course_student DROP INDEX IDMASTERDEGREE_COURSESTUDENT_FK";
		$this->db->query($fk_index);

		$fk_index = "ALTER TABLE course_student DROP INDEX IDDOCTORATE_COURSESTUDENT_FK";
		$this->db->query($fk_index);

		$this->dbforge->drop_column('course_student', 'id_doctorate');
		$this->dbforge->drop_column('course_student', 'id_master_degree');

		// Dropping master_degree and doctorate tables
		$this->dbforge->drop_table('master_degree');
		$this->dbforge->drop_table('doctorate');


	}

	public function down() {
		// Not applicable		
	}

}
