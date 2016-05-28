<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_cria_tabela_course_guest extends CI_Migration {

    public function up() {

        $this->dbforge->add_field(array(
            'id_user' => array('type' => 'INT'),
            'id_course' => array('type' => 'INT'),
            'status' => array('type' => 'varchar(10)')
        ));

		// Creating table course_guest
        $this->dbforge->create_table('course_guest', TRUE);

		// Adding the foreign keys constraints
		$user_foreign_key = "ALTER TABLE course_guest ADD CONSTRAINT IDUSER_COURSEGUEST FOREIGN KEY (id_user) REFERENCES users(id)";
		$course_foreign_key = "ALTER TABLE course_guest ADD CONSTRAINT IDCOURSE_COURSEGUEST FOREIGN KEY (id_course) REFERENCES course(id_course)";
		
		$this->db->query($user_foreign_key);
		$this->db->query($course_foreign_key);

    }

    public function down() {

    }

}