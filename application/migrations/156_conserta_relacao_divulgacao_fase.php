<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_conserta_relacao_divulgacao_fase extends CI_Migration {

    public function up() {

        $disableFk = "SET FOREIGN_KEY_CHECKS=0";
        $this->db->query($disableFk);

        $dropFk = "ALTER TABLE selection_process_divulgation DROP FOREIGN KEY ID_PHASE_DIVULGATION_FK";
        $this->db->query($dropFk);

        $fk = "ALTER TABLE selection_process_divulgation ADD CONSTRAINT ID_PHASE_DIVULGATION_FK FOREIGN KEY (related_id_phase) REFERENCES phase(id_phase)";
        $this->db->query($fk);

        $enableFk = "SET FOREIGN_KEY_CHECKS=1";
        $this->db->query($enableFk);
    }

    public function down(){
    }
}
