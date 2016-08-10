<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_apaga_coluna_id_course_discipline extends CI_Migration {

    public function up() {
        $this->db->query("ALTER TABLE discipline DROP FOREIGN KEY discipline_ibfk_1");
        $this->dbforge->drop_column('discipline', "id_course_discipline");
    }

    public function down(){
    }
}