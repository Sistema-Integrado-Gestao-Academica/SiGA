<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Cria_rota_professor extends CI_Migration {

	public function up() {
		define("TEACHER_GROUP", 5);
		// Create teacher home page
		$this->db->where('id_group', TEACHER_GROUP);
		$this->db->update('group', array('profile_route' => 'mastermind_home'));

	}

	public function down(){

		define("TEACHER_GROUP", 5);
		$this->db->where('id_group', TEACHER_GROUP);
		$this->db->update('group', array('profile_route' => NULL));
		
	}
}
