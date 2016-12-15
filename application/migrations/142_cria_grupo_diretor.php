<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Cria_grupo_diretor extends CI_Migration {

	public function up() {
		
		// Creating guest group
		$guest_group = array('id_group' => 13, 'group_name' => "diretor", 'profile_route' => 'director_home');
		$this->db->insert('group', $guest_group);

		// Creating permission
        $this->db->insert('permission', array(
            'permission_name' => "Definir diretor",
            'route' => "define_director",
            "id_permission" => 41
        ));

        // Creating relation between diretor and admin with the 'Definir diretor' permissions
        $this->db->insert('group_permission', array('id_group' => 3, 'id_permission' => 41));
        $this->db->insert('group_permission', array('id_group' => 13, 'id_permission' => 41));
        
        // Creating relation between diretor and the coordinator permissions
        $this->db->insert('group_permission', array('id_group' => 13, 'id_permission' => 25));
        $this->db->insert('group_permission', array('id_group' => 13, 'id_permission' => 29));
        $this->db->insert('group_permission', array('id_group' => 13, 'id_permission' => 38));
        $this->db->insert('group_permission', array('id_group' => 13, 'id_permission' => 39));
        $this->db->insert('group_permission', array('id_group' => 13, 'id_permission' => 40));
	}

	public function down() {

		// Deleting guest group
		$guest_group = array('id_group' => 13, 'group_name' => "diretor");
		$this->db->delete('group', $guest_group);
	}
}

?>

