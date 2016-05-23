<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Cria_coluna_rota_do_perfil_de_grupos extends CI_Migration {

	public function up() {
		
		$profile_route_column = array(
			'profile_route' => array('type' => 'varchar(20)','NULL' => FALSE, 'default' => "no_route")
		);

		$this->dbforge->add_column('group', $profile_route_column);
		
		$addConstraint = "ALTER TABLE `group` ADD UNIQUE(`group_name`)";
		$this->db->query($addConstraint);
		
		// Adding the student profile route
		$this->db->where('group_name', "estudante");
		$this->db->update('group', array('profile_route' => "student"));

	}

	public function down() {
		$this->dbforge->drop_column('group', "profile_route");
	}
}

?>
