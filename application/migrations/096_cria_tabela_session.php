<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_cria_tabela_session extends CI_Migration {

    public function up() {

        $this->dbforge->add_field(array(
            'session_id' => array('type' => 'varchar(40)', 'default' => "0"),
            'ip_address' => array('type' => 'varchar(45)', 'default' => "0"),
            'user_agent' => array('type' => 'varchar(200)'),
            'last_activity' => array('type' => 'int(10)', 'default' => 0, "unsigned" => TRUE),
            'user_data' => array('type' => 'text')
        ));
        $this->dbforge->add_key('session_id', true);
        $this->dbforge->add_key('last_activity');
        $this->dbforge->create_table('session', TRUE);

    }

    public function down(){

    }
}
