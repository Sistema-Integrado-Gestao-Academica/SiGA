<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Cria_permissao_cadastro_informacoes_estudante extends CI_Migration {

	public function up() {

		// Create studentInformationData permission
		$studentInformationData = array(
				'id_permission' => 13,
				'permission_name' => "Dados Estudante",
				'route' => "student_information"
		);
		$this->db->insert('permission', $studentInformationData);
		
		// Add permission to student
		$addToStudentGroup = array(
				'id_group' => 7,
				'id_permission' => 13
		);
		$this->db->insert('group_permission', $addToStudentGroup);
		
		// Add permission to admin
		$addToAdminGroup = array(
				'id_group' => 3,
				'id_permission' => 13
		);
		$this->db->insert('group_permission', $addToAdminGroup);
		
	}

	public function down(){
		
		$studentInformationData = array(
				'permission_name' => "Dados Estudante",
				'route' => "student_information"
		);
		$this->db->delete('permission', $studentInformationData);
		
		$addToStudentGroup = array(
				'id_group' => 7,
				'id_permission' => 13
		);
		$this->db->delete('group_permission', $addToStudentGroup);
		
		$addToAdminGroup = array(
				'id_group' => 3,
				'id_permission' => 13
		);
		$this->db->delete('group_permission', $addToAdminGroup);
		
	}
}
