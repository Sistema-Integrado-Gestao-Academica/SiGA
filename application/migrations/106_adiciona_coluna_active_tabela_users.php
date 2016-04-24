<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Adiciona_coluna_active_tabela_users extends CI_Migration {

	public function up() {

		$this->dbforge->add_column('users', array(
			'active' => array('type' => 'tinyint(1)', "default" => 0),
		));

		// Activating all existing users till now
		$this->db->update("users", array('active' => 1));
	}

	public function down() {

		$this->dbforge->drop_column('users', 'active');
	}
}
