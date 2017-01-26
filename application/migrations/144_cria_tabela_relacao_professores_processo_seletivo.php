<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Cria_tabela_relacao_professores_processo_seletivo extends CI_Migration {

    public function up() {

        $this->dbforge->add_field(array(
            'id_process' => array('type' => 'INT'),
            'id_teacher' => array('type' => 'INT')
        ));

        $this->dbforge->create_table('teacher_selection_process', true);

        $fk = "ALTER TABLE teacher_selection_process ADD CONSTRAINT ID_PROCESS_TEACHER_FK FOREIGN KEY (id_process) REFERENCES selection_process(id_process)";
        $this->db->query($fk);

        $fk = "ALTER TABLE teacher_selection_process ADD CONSTRAINT ID_TEACHER_PROCESS_FK FOREIGN KEY (id_teacher) REFERENCES users(id)";
        $this->db->query($fk);
    }

    public function down(){
        $this->dbforge->drop_table('teacher_selection_process', true);
    }
}