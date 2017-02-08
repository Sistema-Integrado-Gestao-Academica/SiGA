<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Cria_view_divulgacao_processo_seletivo extends CI_Migration {

	public function up() {

        $query = "CREATE VIEW view_open_selection_process AS 
                SELECT DISTINCT selection_process.* FROM selection_process 
                JOIN  selection_process_divulgation 
                    ON ((selection_process_divulgation.date <= NOW()) 
                    AND (selection_process_divulgation.id_process = selection_process.id_process) AND (selection_process_divulgation.initial_divulgation = TRUE))
                WHERE (selection_process.end_date >= NOW())";
        $this->db->query($query);
	}

	public function down(){
	}
}