<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_adiciona_permissao_processo_seletivo_docente extends CI_Migration {

    public function up() {

        // Creating permission
        $this->db->insert('permission', array(
            'permission_name' => "Processos Seletivos",
            'route' => "selection_process_evaluation",
            'id_permission' => 46
        ));

        // Adding permission to guest group
        $this->db->insert('group_permission', array('id_group' => 5, 'id_permission' => 46));
    }

    public function down() {
        $this->db->delete('group_permission', array('id_group' => 5, 'id_permission' => 46));
        $this->db->delete('permission', array('id_permission' => 46));
    }
}