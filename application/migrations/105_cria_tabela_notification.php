<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH."/constants/SelectionProcessConstants.php");

class Migration_cria_tabela_notification extends CI_Migration {

	public function up() {

		// Creating notification table
		$this->dbforge->add_field(array(
			'id_notification' => array('type' => 'INT', 'auto_increment' => true),
			'id_user' => array('type' => "INT"),
			'content' => array('type' => 'VARCHAR(50)'),
			'seen' => array('type' => 'TINYINT(1)'),
			'link' => array('type' => 'TEXT', "null" => TRUE),
			'type' => array('type' => 'VARCHAR(30)'),
			'time' => array('type' => 'TIMESTAMP', "default" => "0000-00-00 00:00:00", "null" => FALSE)
		));
		$this->dbforge->add_key('id_notification', true);
		$this->dbforge->create_table('notification', TRUE);
		
		// Adding course FK
		$addConstraint = "ALTER TABLE notification ADD CONSTRAINT NOTIFICATION_USER_FK FOREIGN KEY (id_user) REFERENCES users(id) ON DELETE CASCADE ON UPDATE RESTRICT";
		$this->db->query($addConstraint);
	}

	public function down(){
		
		$dropConstraint = "ALTER TABLE notification DROP FOREIGN KEY NOTIFICATION_USER_FK";
		$this->db->query($dropConstraint);

		$this->dbforge->drop_table('notification');
	}
}
