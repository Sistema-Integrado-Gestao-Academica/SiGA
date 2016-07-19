<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_cria_tabela_e_permissao_projeto extends CI_Migration {

    public function up() {

        // Creating permission
        $this->db->insert('permission', array('permission_name' => "Projetos", 'route' => "academic_projects", "id_permission"=>35));

        // Adding permission to teacher
        $this->db->insert('group_permission', array('id_group' => 5, 'id_permission' => 35));

        // Projects table
        $this->dbforge->add_field(array(
            'id' => array('type' => 'INT', 'auto_increment' => TRUE),
            'financing' => array('type' => 'VARCHAR(60)'),
            'name' => array('type' => 'VARCHAR(200)'),
            'start_date' => array('type' => 'date'),
            'end_date' => array('type' => 'date', 'NULL' => TRUE),
            'study_object' => array('type' => 'TEXT', 'NULL' => TRUE),
            'justification' => array('type' => 'TEXT', 'NULL' => TRUE),
            'procedures' => array('type' => 'TEXT', 'NULL' => TRUE),
            'expected_results' => array('type' => 'TEXT', 'NULL' => TRUE),
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('academic_project', TRUE);

        // Project team table
        $this->dbforge->add_field(array(
            'id_project' => array('type' => 'INT'),
            'member' => array('type' => 'INT'),
            'coordinator' => array('type' => 'TINYINT(1)'),
        ));
        $this->dbforge->create_table('project_team', TRUE);

        $this->db->query("ALTER TABLE project_team ADD CONSTRAINT ID_USER_MEMBER_FK FOREIGN KEY (member) REFERENCES users(id)");
    }

    public function down() {

    }
}