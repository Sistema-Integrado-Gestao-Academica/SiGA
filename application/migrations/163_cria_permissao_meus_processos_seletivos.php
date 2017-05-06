<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_cria_permissao_meus_processos_seletivos extends CI_Migration {

    public function up() {

        // Creating permission
        $this->db->insert('permission', array(
            'permission_name' => "Meus Processos Seletivos",
            'route' => "selection_process/my_processes",
            'id_permission' => 47
        ));

        // Adding permission to guest group
        $this->db->insert('group_permission', ['id_group' => 8, 'id_permission' => 47]);
    }

    public function down() {
        $this->db->delete('group_permission', ['id_group' => 8, 'id_permission' => 47]);
        $this->db->delete('permission', ['id_permission' => 47]);
    }

}