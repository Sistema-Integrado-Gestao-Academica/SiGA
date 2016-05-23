<?php
class Migration_Cria_permissao_relatorio_usuarios extends CI_migration {

	public function up() {
		
		// Create user_report permission
		$userReport = array(
			'id_permission' => 10,
			'permission_name' => "Usuários",
			'route' => "user_report"
		);
		$this->db->insert('permission', $userReport);
		
		// Add permission to admin
		$addToAdminGroup = array(
			'id_group' => 3,
			'id_permission' => 10
		);
		$this->db->insert('group_permission', $addToAdminGroup);

	}

	public function down() {

		$userReport = array(
			'permission_name' => "Usuários",
			'route' => "user_report"
		);
		$this->db->delete('permission', $userReport);
		
		$addToAdminGroup = array(
			'id_group' => 3,
			'id_permission' => 10
		);
		$this->db->delete('group_permission', $addToAdminGroup);
	}
}
