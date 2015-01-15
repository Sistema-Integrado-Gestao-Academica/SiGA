<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Adiciona_rota_do_perfil_secretaria extends CI_Migration {

	public function up() {
		
		// Adding secretary profile
		$secretary_profile_route = array('profile_route' => "secretary_home");
		$this->db->where('group_name', "secretario");
		$this->db->update('group', $secretary_profile_route);

	}

	public function down() {

		// Deleting secretary profile
		$secretary_profile_route = array('profile_route' => "");
		$this->db->where('group_name', "secretario");
		$this->db->update('group', $secretary_profile_route);

	}
}

?>

