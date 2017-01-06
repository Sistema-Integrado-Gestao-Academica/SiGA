<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Cria_tabela_divulgacao_processo_seletivo extends CI_Migration {

	public function up() {

        $this->dbforge->add_field(array(
            'id' => array('type' => 'INT', 'auto_increment' => true),
            'description' => array('type' => 'VARCHAR(150)'),
            'message' => array('type' => 'text', 'NULL' => true),
            'date' => array('type' => 'date'),
            'file_path' => array('type' => 'text', 'NULL' => true),
            'initial_divulgation' => array('type' => 'tinyint'),
            'related_id_phase' => array('type' => 'INT', 'NULL' => true),
            'id_process' => array('type' => 'INT')
        ));

        $this->dbforge->add_key('id', true);
        $this->dbforge->create_table('selection_process_divulgation', true);

        $fk = "ALTER TABLE selection_process_divulgation ADD CONSTRAINT ID_PROCESS_DIVULGATION_FK FOREIGN KEY (id_process) REFERENCES selection_process(id_process)";
        $this->db->query($fk);

        $fk = "ALTER TABLE selection_process_divulgation ADD CONSTRAINT ID_PHASE_DIVULGATION_FK FOREIGN KEY (related_id_phase) REFERENCES process_phase(id_phase)";
        $this->db->query($fk);

        $this->dbforge->add_column('process_phase', array(
            'start_date' => array('type' => 'date', "NULL" => True),
            'end_date' => array('type' => 'date', "NULL" => True)
        ));
	}

	public function down(){
	}
}