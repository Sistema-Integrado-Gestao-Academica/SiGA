<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_altera_tabela_session extends CI_Migration {

    public function up() {

        $this->dbforge->drop_table("session");

        $this->dbforge->add_field(array(
            'id' => array('type' => 'varchar(40)', 'default' => "0"),
            'ip_address' => array('type' => 'varchar(45)', 'default' => "0"),
            'timestamp' => array('type' => 'int(10)', 'unsigned' => TRUE, 'default' => "0"),
            'data' => array('type' => 'blob')
        ));
        $this->dbforge->add_key('timestamp', true);
        $this->dbforge->create_table('session', TRUE);

    }

    public function down(){

    }
}
