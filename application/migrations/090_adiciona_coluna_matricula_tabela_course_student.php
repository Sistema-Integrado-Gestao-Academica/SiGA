<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_adiciona_coluna_matricula_tabela_course_student extends CI_Migration {

    public function up() {

        $this->dbforge->add_column('course_student', array(
            'enrollment' => array('type' => "varchar(9)")
        ));

        $uk = "ALTER TABLE course_student ADD CONSTRAINT ENROLLMENT_UK UNIQUE (enrollment)";
        $this->db->query($uk);
    }

    public function down() {

    }

}