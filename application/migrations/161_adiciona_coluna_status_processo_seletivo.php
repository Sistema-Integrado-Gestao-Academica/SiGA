<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_adiciona_coluna_status_processo_seletivo extends CI_Migration {

    public function up() {
        $this->dbforge->add_column('selection_process', [
            'status' => ['type' => 'varchar(40)', 'null' => TRUE]
        ]);

        $this->fillExistentProcess();

        $this->dbforge->drop_column('selection_process_evaluation', 'approved');

    }

    public function down() {
        $this->dbforge->drop_column('selection_process', 'status');
    }

    private function fillExistentProcess(){

        $processes = $this->db->get("selection_process")->result_array();
        $this->load->helper('selectionprocess');
        $this->load->model('program/selectiveprocess_model', 'process_model');

        if(!empty($processes)){
            foreach ($processes as $process) {
                $processObj = $this->process_model->convertArrayToObject($process);
                $status = getProcessStatusByDate($processObj);

                $this->db->where("id_process", $process['id_process']);
                $this->db->update("selection_process", array('status' => $status));
            }
        }

    }
}