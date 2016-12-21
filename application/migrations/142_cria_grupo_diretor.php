<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Cria_grupo_diretor extends CI_Migration {

	public function up() {
		
		// Creating director group
		$director_group = array('id_group' => 13, 'group_name' => "diretor", 'profile_route' => 'director_home');
		$this->db->insert('group', $director_group);

		// Creating permission for define director
        $this->db->insert('permission', array(
            'permission_name' => "Definir diretor",
            'route' => "define_director",
            "id_permission" => 41
        ));

        // Creating permissions for director reports 
        $this->db->insert('permission', array(
            'permission_name' => "Relatório de produções",
            'route' => "production_report_director",
            "id_permission" => 42
        ));


        $this->db->insert('permission', array(
            'permission_name' => "Relatório de avaliações", 
            'route' => "evaluation_report_director",
            "id_permission" => 43
        ));

        $this->db->insert('permission', array(
            'permission_name' => "Relatório de preenchimento de produções",
            'route' => "productions_fill_report_director",
            "id_permission" => 44
        ));

        // // Creating relation between diretor and admin with the 'Definir diretor' permissions
        $this->db->insert('group_permission', array('id_group' => 3, 'id_permission' => 41));
        $this->db->insert('group_permission', array('id_group' => 13, 'id_permission' => 41));
        
        // Creating relation between diretor and the intellectual production reports
        $this->db->insert('group_permission', array('id_group' => 13, 'id_permission' => 42));
        $this->db->insert('group_permission', array('id_group' => 13, 'id_permission' => 43));
        $this->db->insert('group_permission', array('id_group' => 13, 'id_permission' => 44));
	}

	public function down() {

		// Deleting director group
		$director_group = array('id_group' => 13, 'group_name' => "diretor");
		$this->db->delete('group', $director_group);
	}
}

?>

