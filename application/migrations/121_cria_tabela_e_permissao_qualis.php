<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Cria_tabela_e_permissao_qualis extends CI_Migration {

    public function up() {

        // Qualis table
        $this->dbforge->add_field(array(
            'id' => array('type' => 'INT', 'auto_increment' => true),
            'issn' => array('type' => 'VARCHAR(9)'),
            'periodic' => array('type' => 'TEXT'),
            'qualis' => array('type' => 'VARCHAR(2)'),
            'area' => array('type' => 'VARCHAR(100)'),
        ));

        $this->dbforge->add_key('id', true);
        $this->dbforge->create_table('periodic_qualis', true);

        $this->db->query("ALTER TABLE periodic_qualis ADD CONSTRAINT ISSN_UK UNIQUE (issn)");

        // Create import qualis permission
        $this->db->insert("permission", array(
            'id_permission' => 33,
            'permission_name' => "Importar Qualis",
            'route' => "import_qualis"
        ));

        // Add this permission to academic secretary
        $this->db->insert('group_permission', array(
            'id_group' => 11,
            'id_permission' => 33
        ));

    }

    public function down() {
        $this->dbforge->drop_table('periodic_qualis');
    }

}

