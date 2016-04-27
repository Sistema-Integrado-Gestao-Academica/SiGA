<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_adiciona_constraint_unique_tabela_users extends CI_Migration {

	public function up() {

		$uk = "ALTER TABLE users ADD CONSTRAINT USERS_UK UNIQUE (cpf, email, login)";
		$this->db->query($uk);
	}

	public function down() {

		$uk = "ALTER TABLE users DROP INDEX USERS_UK";
		$this->db->query($uk);
	}
}
