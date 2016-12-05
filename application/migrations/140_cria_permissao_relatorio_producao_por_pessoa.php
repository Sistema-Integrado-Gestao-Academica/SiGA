<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_cria_permissao_relatorio_producao_por_pessoa extends CI_Migration {

    public function up() {

        // Creating permission
        $this->db->insert('permission', array(
            'permission_name' => "Relatório de preenchimento de produções",
            'route' => "productions_fill_report",
            "id_permission" => 40
        ));

        // Creating relation between coordinator, secretary and permission
        $this->db->insert('group_permission', array('id_group' => 9, 'id_permission' => 40));
        $this->db->insert('group_permission', array('id_group' => 11, 'id_permission' => 40));
    }

    public function down() {

    }

}