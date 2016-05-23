<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_seta_enrollment_null extends CI_Migration {

	public function up() {

		$this->dbforge->modify_column('course_student', array(
			'enrollment' => array('type'=> "varchar(9)", 'null' => TRUE)
		));

		$uk = "ALTER TABLE course_student ADD CONSTRAINT USER_COURSE_UK UNIQUE (id_course, id_user)";
		$this->db->query($uk);
	}

	public function down(){
		
	}
}