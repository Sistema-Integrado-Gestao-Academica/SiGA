<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_cria_permissao_convidar_usuario extends CI_Migration {

	public function up() {
		
		// Creating permission
		$this->db->insert('permission', array('permission_name' => "Convidar usuários", 'route' => "invite_user", "id_permission"=>31));
		
		// Adding permission to academic secretary
		$this->db->insert('group_permission', array('id_group' => 11, 'id_permission' => 31));

		// Create invitation table
		$this->dbforge->add_field(array(
            'id_invitation' => array('type' => 'VARCHAR(41)'),
            'id_secretary' => array('type' => 'INT'),
            'invited_group' => array('type' => 'INT'),
            'invited_email' => array('type' => 'VARCHAR(50)'),
            'active' => array('type' => 'TINYINT(1)', 'default' => 0),
            'time' => array('type' => 'TIMESTAMP', "default" => "0000-00-00 00:00:00", "null" => FALSE)
        ));
		$this->dbforge->add_key('id_invitation', TRUE);
        $this->dbforge->create_table('user_invitation', TRUE);

        // Set current timestamp as default value
		$addCurrentTimeStamp = "ALTER TABLE user_invitation CHANGE time time TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP";
		$this->db->query($addCurrentTimeStamp);

		// Adding the foreign keys constraints
		$fk = "ALTER TABLE user_invitation ADD CONSTRAINT USER_INVITATION_FK FOREIGN KEY (id_secretary) REFERENCES users(id)";
		$this->db->query($fk);
	}

	public function down() {
		
	}

}