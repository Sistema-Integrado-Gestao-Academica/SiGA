<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Adiciona_colunas_phones_tabela_users extends CI_Migration {

	public function up() {

		$this->dbforge->add_column('users', array(
			'home_phone' => array('type' => 'varchar(11)', "null" => TRUE),
			'cell_phone' => array('type' => 'varchar(11)', "null" => TRUE)
		));

	}

	public function down() {

		$this->dbforge->drop_column('users', 'home_phone');
		$this->dbforge->drop_column('users', 'cell_phone');
	}
}
