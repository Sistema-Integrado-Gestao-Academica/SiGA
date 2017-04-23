<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Adiciona_coluna_linha_de_pesquisa_inscricao_processo_seletivo extends CI_Migration {

    public function up() {
        $this->dbforge->add_column('selection_process_user_subscription', [
            'research_line' => ['type' => 'INT'],
            'confirmed' => ['type' => 'tinyint(1)', 'default' => false]
        ]);

        $fk = "ALTER TABLE selection_process_user_subscription ADD CONSTRAINT RESEARCH_LINE_FK FOREIGN KEY (research_line) REFERENCES research_lines(id_research_line)";
        $this->db->query($fk);
    }

    public function down(){
        $this->dbforge->drop_column('selection_process_user_subscription', "research_line");
        $this->dbforge->drop_column('selection_process_user_subscription', "confirmed");
    }
}
