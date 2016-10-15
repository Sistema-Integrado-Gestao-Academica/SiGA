<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_cria_permissao_relatorio_avaliacao_programa extends CI_Migration {

    public function up() {

        // Creating permission
        $this->db->insert('permission', array('permission_name' => "Relatório de avaliações", 'route' => "evaluation_report", "id_permission" => 39));

        // Creating relation between coordinator and evaluation report permission
        $this->db->insert('group_permission', array('id_group' => 9, 'id_permission' => 39));
    }

    public function down() {

    }

}