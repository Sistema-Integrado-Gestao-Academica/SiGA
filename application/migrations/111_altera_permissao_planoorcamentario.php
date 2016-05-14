<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_altera_permissao_planoorcamentario extends CI_Migration {

	public function up() {
		
		$this->db->where("route","planoorcamentario");
		$this->db->update('permission', array('route' => "budgetplan"));
	}

	public function down() {
		
	}
}