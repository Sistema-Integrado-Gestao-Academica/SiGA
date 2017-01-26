<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Cria_tabela_campo_programa_portal extends CI_Migration {

	public function up() {

        $this->dbforge->add_field(array(
            'id' => array('type' => 'INT', 'auto_increment' => true),
            'title' => array('type' => 'VARCHAR(50)'),
            'details' => array('type' => 'text', 'NULL' => true),
            'file_path' => array('type' => 'text', 'NULL' => true),
            'visible' => array('type' => 'tinyint'),
            'id_program' => array('type' => 'INT')
        ));

        $this->dbforge->add_key('id', true);
        $this->dbforge->create_table('program_portal_field', true);

        $fk = "ALTER TABLE program_portal_field ADD CONSTRAINT ID_PROGRAM_PORTAL_FK FOREIGN KEY (id_program) REFERENCES program(id_program)";
        $this->db->query($fk);

	}

	public function down(){
	}
}