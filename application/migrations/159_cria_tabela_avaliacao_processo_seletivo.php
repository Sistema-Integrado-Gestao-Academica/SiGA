<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_cria_tabela_avaliacao_processo_seletivo extends CI_Migration {

    public function up() {

        $addAsKey = "ALTER TABLE process_phase ADD id int NOT NULL AUTO_INCREMENT primary key FIRST";
        $this->db->query($addAsKey);

        // Creating evaluation candidate teacher table
        $this->dbforge->add_field(array(
            'id_subscription' => ['type' => 'INT'],
            'id_teacher' => ['type' => 'INT'],
            'grade' => ['type' => 'INT', 'null' => TRUE],
            'id_process_phase' => ['type' => 'INT'],
            'approved' => ['type' => 'tinyint(1)', 'default' => TRUE],
        ));
        
        $this->dbforge->add_key(array('id_subscription', 'id_teacher', 'process_phase_id'));
        $this->dbforge->create_table('selection_process_evaluation', TRUE);

        $subscriptionIdFk = "ALTER TABLE selection_process_evaluation ADD CONSTRAINT IDSUBSCRIPTION_EVALUATION_FK FOREIGN KEY (id_subscription) REFERENCES selection_process_user_subscription(id)";
        $this->db->query($subscriptionIdFk);

        $teacherIdFk = "ALTER TABLE selection_process_evaluation ADD CONSTRAINT EVALUATION_TEACHERID_FK FOREIGN KEY (id_teacher) REFERENCES users(id)";
        $this->db->query($teacherIdFk);

        $processPhaseIdFk = "ALTER TABLE selection_process_evaluation ADD CONSTRAINT EVALUATION_PHASEID_FK FOREIGN KEY (id_process_phase) REFERENCES process_phase(id)";
        $this->db->query($processPhaseIdFk);

    }

    public function down() {
        $this->dbforge->drop_table('selection_process_evaluation');
    }
}