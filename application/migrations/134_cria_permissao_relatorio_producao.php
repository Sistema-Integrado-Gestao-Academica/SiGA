<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_cria_permissao_relatorio_producao extends CI_Migration {

    public function up() {

        // Creating permission
        $this->db->insert('permission', array('permission_name' => "Relatório de produções", 'route' => "production_report", "id_permission" => 38));

        // Creating relation between coordinator and production report permission
        $this->db->insert('group_permission', array('id_group' => 9, 'id_permission' => 38));
    }

    public function down() {

    }

}