<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Adiciona_coluna_active_tabela_users extends CI_Migration {

	public function up() {

		// 
		$this->dbforge->add_column('users', array(
			'active' => array('type' => 'tinyint(1)', "default" => 0)
		));
		// Activating all existing users till now
		$this->db->update("users", array('active' => 1));

		// Creating user_activation table

		$this->dbforge->add_field(array(
			'id_user' => array('type' => "INT"),
			'activation' => array('type' => 'VARCHAR(41)'),
			'time' => array('type' => 'TIMESTAMP', "default" => "0000-00-00 00:00:00", "null" => FALSE)
		));
		$this->dbforge->create_table('user_activation', TRUE);
		
		// Adding id_user FK
		$addConstraint = "ALTER TABLE user_activation ADD CONSTRAINT ACTIVATION_USER_FK FOREIGN KEY (id_user) REFERENCES users(id) ON DELETE CASCADE ON UPDATE RESTRICT";
		$this->db->query($addConstraint);

		// Set current timestamp as default value
		$addCurrentTimeStamp = "ALTER TABLE user_activation CHANGE time time TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP";
		$this->db->query($addCurrentTimeStamp);

		$uk = "ALTER TABLE user_activation ADD CONSTRAINT ACTIVATION_HASH_UNIQUE UNIQUE(activation)";
		$this->db->query($uk);

	}

	public function down() {

		$this->dbforge->drop_column('users', 'active');

		$dropConstraint = "ALTER TABLE user_activation DROP FOREIGN KEY ACTIVATION_USER_FK";
		$this->db->query($dropConstraint);

		$this->dbforge->drop_table('user_activation');
	}
}
