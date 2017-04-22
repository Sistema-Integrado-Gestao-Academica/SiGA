<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_cria_tabela_documentos_processo_seletivo extends CI_Migration {

    public function up() {

        // Creating all available docs table
        $this->dbforge->add_field(array(
            'id' => array('type' => 'INT', 'auto_increment' => TRUE),
            'doc_name' => array('type' => 'VARCHAR(70)'),
            'doc_desc' => array('type' => 'TEXT', 'null' => TRUE)
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('selection_process_available_docs', TRUE);

        // Creating table to relate process with its needed docs
        $this->dbforge->add_field(array(
            'id_process' => array('type' => 'INT'),
            'id_doc' => array('type' => 'INT'),
        ));
        $this->dbforge->create_table('selection_process_needed_docs', TRUE);

        $processIdFk = "ALTER TABLE selection_process_needed_docs ADD CONSTRAINT NEEDED_DOCS_PROCESSID_FK FOREIGN KEY (id_process) REFERENCES selection_process(id_process)";
        $this->db->query($processIdFk);

        $docIdFk = "ALTER TABLE selection_process_needed_docs ADD CONSTRAINT NEEDED_DOCS_DOCID_FK FOREIGN KEY (id_doc) REFERENCES selection_process_available_docs(id)";
        $this->db->query($docIdFk);

        // Inserting all possible documents
        $this->populateAllDocs();
    }

    public function down() {
        $this->dbforge->drop_table('selection_process_needed_docs');
        $this->dbforge->drop_table('selection_process_available_docs');
    }

    private function populateAllDocs(){
        $this->db->insert_batch('selection_process_available_docs', [
            [
                'doc_name' => 'Termo de confirmação de interesse pela vaga',
                'doc_desc' => ''
            ],
            [
                'doc_name' => 'Termo de compromisso',
                'doc_desc' => ''
            ],
            [
                'doc_name' => 'Diploma do curso superior',
                'doc_desc' => ''
            ],
            [
                'doc_name' => 'Histórico escolar',
                'doc_desc' => ''
            ],
            [
                'doc_name' => 'Carteira de identidade',
                'doc_desc' => ''
            ],
            [
                'doc_name' => 'CPF',
                'doc_desc' => ''
            ],
            [
                'doc_name' => 'Título de eleitor',
                'doc_desc' => 'Cópia do título com o último comprovante de votação.'
            ],
            [
                'doc_name' => 'Certificado de reservista',
                'doc_desc' => 'Apenas para candidatos do sexo masculino.'
            ],
            [
                'doc_name' => 'Carteira de identidade de estrangeiro',
                'doc_desc' => 'Apenas para estrangeiros.'
            ],
            [
                'doc_name' => 'Pré-projeto de pesquisa',
                'doc_desc' => ''
            ],
            [
                'doc_name' => 'Currículo Lattes',
                'doc_desc' => 'Apenas o link para o currículo.'
            ],
            [
                'doc_name' => 'Comprovante de Proficiência em língua(s) estrangeira(s)',
                'doc_desc' => ''
            ],
            [
                'doc_name' => 'Comprovante de pagamento da GRU',
                'doc_desc' => 'Cópia digitalizada do comprovante de pagamento da Guia de Recolhimento da União (GRU) no valor de R$ 250,00.'
            ],
        ]);
    }
}