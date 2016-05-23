<?php
class Migration_Cria_tabela_de_grupos_de_usuario extends CI_migration {

	public function up() {
	
		$this->dbforge->add_field(array(
			'id_user' => array('type' => 'INT'),
			'id_group' => array('type' => 'INT')
		));
		$this->dbforge->create_table('user_group', true);

		// Adding the foreign keys constraints
		$add_foreign_key = "ALTER TABLE user_group ADD CONSTRAINT IDUSER_USERGROUP FOREIGN KEY (id_user) REFERENCES users(id)";
		$this->db->query($add_foreign_key);
		
		// $add_foreign_key = "ALTER TABLE user_group ADD CONSTRAINT IDGROUP_USERGROUP FOREIGN KEY (id_group) REFERENCES group (id_group);";
		// $this->db->query($add_foreign_key);

/*		$create_table_user_group = "
			CREATE TABLE user_group(
				id_user INT NOT NULL AUTO_INCREMENT,
				id_group INT NOT NULL AUTO_INCREMENT,

				CONSTRAINT IDUSER_USERGROUP_FK FOREIGN KEY (id_user) REFERENCES users(id),
				CONSTRAINT IDGROUP_USERGROUP_FK FOREIGN KEY (id_group) REFERENCES group(id_group)

			);";
		
		$this->db->query($create_table_user_group);*/


	// Inserting user_group values
		$user_group_value = array('id_user' => 1, 'id_group' => 3);
		$this->db->insert('user_group', $user_group_value);

		$user_group_value = array('id_user' => 2, 'id_group' => 1);
		$this->db->insert('user_group', $user_group_value);

		$user_group_value = array('id_user' => 3, 'id_group' => 2);
		$this->db->insert('user_group', $user_group_value);
		
		$user_group_value = array('id_user' => 4, 'id_group' => 6);
		$this->db->insert('user_group', $user_group_value);
		
		$user_group_value = array('id_user' => 4, 'id_group' => 1);
		$this->db->insert('user_group', $user_group_value);
	// End

	}

	public function down() {
		$this->dbforge->drop_table('user_group');
	}
}
