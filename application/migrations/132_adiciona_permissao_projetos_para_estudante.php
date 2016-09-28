<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_adiciona_permissao_projetos_para_estudante extends CI_Migration {

    public function up() {

        $this->db->query("ALTER TABLE project_team ADD CONSTRAINT ID_PROJECT_FK FOREIGN KEY (id_project) REFERENCES academic_project(id) ON DELETE CASCADE");

        $this->db->insert('group_permission', array('id_group' => 7, 'id_permission' => 35));

        $this->dbforge->add_column('project_team', array(
            'coordinator_activation' => array('type' => 'varchar(40)', 'NULL' => TRUE, 'default'=>NULL),
            'owner' => array('type' => 'tinyint(1)', 'NULL' => FALSE, 'default'=>FALSE),
        ));

        $uk = "ALTER TABLE project_team ADD CONSTRAINT COORDINATOR_ACTIVATION_UK UNIQUE(coordinator_activation)";
        $this->db->query($uk);
    }

    public function down() {

    }
}