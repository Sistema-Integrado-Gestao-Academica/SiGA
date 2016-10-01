<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_adiciona_coluna_enroll_semester_tabela_course_student extends CI_Migration {

    public function up() {

        $this->dbforge->add_column('course_student', array(
            'enroll_semester' => array('type' => "INT", 'NULL' => TRUE)
        ));

        $fk = "ALTER TABLE course_student ADD CONSTRAINT ENROLL_SEMESTER_FK FOREIGN KEY (enroll_semester) REFERENCES semester(id_semester)";
        $this->db->query($fk);

        
    }

    public function down() {

    }

}