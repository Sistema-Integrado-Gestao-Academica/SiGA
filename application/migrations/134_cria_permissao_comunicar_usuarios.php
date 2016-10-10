<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_cria_permissao_comunicar_usuarios extends CI_Migration {

    public function up() {

        # Creating permission
        $this->db->insert('permission', array('permission_name' => "Notificar usuários", 'route' => "notify_users", "id_permission" => 37));

        #creating relation between academic secretary and offered disciplines permission
        $this->db->insert('group_permission', array('id_group' => 11, 'id_permission' => 37));
    }

    public function down() {

    }

}