<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Adiciona_coluna_temporary_password_tabela_users extends CI_Migration {

	public function up() {

		$this->dbforge->add_column('users', array(
			'temporary_password' => array('type' => 'tinyint')
		));

	}

	public function down() {

		$this->dbforge->drop_column('users', 'temporary_password');
	}
}
