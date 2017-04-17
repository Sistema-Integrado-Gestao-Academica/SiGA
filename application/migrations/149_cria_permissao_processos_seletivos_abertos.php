<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_cria_permissao_processos_seletivos_abertos extends CI_Migration {

    public function up() {

        // Creating permission
        $this->db->insert('permission', array(
            'permission_name' => "Processos Seletivos abertos",
            'route' => "selection_process/public",
            'id_permission' => 45
        ));

        // Adding permission to guest group
        $this->db->insert('group_permission', array('id_group' => 8, 'id_permission' => 45));
    }

    public function down() {
        $this->db->delete('group_permission', array('id_group' => 8, 'id_permission' => 45));
        $this->db->delete('permission', array('id_permission' => 45));
    }

}