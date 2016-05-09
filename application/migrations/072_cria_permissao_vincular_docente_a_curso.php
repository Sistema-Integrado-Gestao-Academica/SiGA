<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Cria_permissao_vincular_docente_a_curso extends CI_Migration {

	public function up() {

		$this->db->insert('permission', array(
			'id_permission' => 22,
			'permission_name' => "Docentes dos cursos",
			'route' => "enroll_teacher"
		));

		$this->db->insert('group_permission', array(
			'id_group' => 11,
			'id_permission' => 22
		));
	}

	public function down(){
		
	}
}
