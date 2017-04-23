<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_cria_tabela_inscricao_processo_seletivo extends CI_Migration {

    public function up() {

        // Creating user subscription table
        $this->dbforge->add_field(array(
            'id' => ['type' => 'INT', 'auto_increment' => TRUE],
            'id_user' => ['type' => 'INT'],
            'id_process' => ['type' => 'INT'],
            'candidate_id' => ['type' => 'varchar(7)'],
            'full_name' => ['type' => 'varchar(70)'],
            'sex' => ['type' => 'varchar(6)'],
            'birth_date' => ['type' => 'DATE'],
            'email' => ['type' => 'varchar(60)'],
            'nationality' => ['type' => 'varchar(30)'],
            'address_place' => ['type' => 'varchar(80)'],
            'address_city' => ['type' => 'varchar(40)'],
            'address_state' => ['type' => 'varchar(40)'],
            'address_cep' => ['type' => 'varchar(10)'],
            'address_country' => ['type' => 'varchar(2)'],
            'contact_ddd_home' => ['type' => 'varchar(4)'],
            'contact_number_home' => ['type' => 'varchar(15)'],
            'contact_ddd_mobile' => ['type' => 'varchar(4)'],
            'contact_number_mobile' => ['type' => 'varchar(15)'],
            'special_needs' => ['type' => 'TEXT', 'null' => TRUE]
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('selection_process_user_subscription', TRUE);

        $processIdFk = "ALTER TABLE selection_process_user_subscription ADD CONSTRAINT SUBSCRIPTION_PROCESSID_FK FOREIGN KEY (id_process) REFERENCES selection_process(id_process)";
        $this->db->query($processIdFk);

        $userIdFk = "ALTER TABLE selection_process_user_subscription ADD CONSTRAINT SUBSCRIPTION_USERID_FK FOREIGN KEY (id_user) REFERENCES users(id)";
        $this->db->query($userIdFk);

        $userProcessUk = "ALTER TABLE selection_process_user_subscription ADD CONSTRAINT SUBSCRIPTION_USER_PROCESS_UK UNIQUE(id_user, id_process)";
        $this->db->query($userProcessUk);

        $candidateIdUk = "ALTER TABLE selection_process_user_subscription ADD CONSTRAINT SUBSCRIPTION_CANDIDATEID_UK UNIQUE(candidate_id)";
        $this->db->query($candidateIdUk);


        // Creating table to relate subscrition with its needed docs
        $this->dbforge->add_field(array(
            'id_subscription' => array('type' => 'INT'),
            'id_doc' => array('type' => 'INT'),
            'doc_path' => array('type' => 'TEXT')
        ));
        $this->dbforge->create_table('selection_process_subscription_docs', TRUE);

        $subscriptionIdFk = "ALTER TABLE selection_process_subscription_docs ADD CONSTRAINT SUBSCRIPTION_DOCS_SUBSCRID_FK FOREIGN KEY (id_subscription) REFERENCES selection_process_user_subscription(id)";
        $this->db->query($subscriptionIdFk);

        $docIdFk = "ALTER TABLE selection_process_subscription_docs ADD CONSTRAINT SUBSCRIPTION_DOCS_DOCID_FK FOREIGN KEY (id_doc) REFERENCES selection_process_available_docs(id)";
        $this->db->query($docIdFk);

        $subscriptionDocUk = "ALTER TABLE selection_process_subscription_docs ADD CONSTRAINT SUBSCRIPTION_DOCS_UK UNIQUE(id_subscription, id_doc)";
        $this->db->query($subscriptionDocUk);
    }

    public function down() {
        $this->dbforge->drop_table('selection_process_subscription_docs');
        $this->dbforge->drop_table('selection_process_user_subscription');
    }
}