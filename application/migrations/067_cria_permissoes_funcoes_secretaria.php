<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Cria_permissoes_funcoes_secretaria extends CI_Migration {

	public function up() {

		$this->dbforge->modify_column('permission', array(
			'permission_name' => array('type' => "varchar(50)")
		));

		$this->db->insert_batch('permission', array(
			array(
				'id_permission' => 15,
				'permission_name' => "Matricular Alunos",
				'route' => "enroll_student"
			),
			array(
				'id_permission' => 16,
				'permission_name' => "Lista de Alunos",
				'route' => "student_list"
			),
			array(
				'id_permission' => 17,
				'permission_name' => "Relatório de Solicitações",
				'route' => "request_report"
			),
			array(
				'id_permission' => 18,
				'permission_name' => "Listas de Oferta",
				'route' => "offer_list"
			),
			array(
				'id_permission' => 19,
				'permission_name' => "Currículos de cursos",
				'route' => "course_syllabus"
			),
			array(
				'id_permission' => 20,
				'permission_name' => "Definir Orientadores",
				'route' => "enroll_mastermind"
			)
		));
		
		// Deleting the permissions "Funcionarios", "Setores" and "Funcoes" from the academic secretary group
		$this->db->delete('group_permission', array('id_group' => "11", 'id_permission' => "2"));
		$this->db->delete('group_permission', array('id_group' => "11", 'id_permission' => "3"));
		$this->db->delete('group_permission', array('id_group' => "11", 'id_permission' => "4"));
		$this->db->delete('group_permission', array('id_group' => "11", 'id_permission' => "5"));
		
		// Deleting the permissions "Funcionarios" and "Funcoes" from the financial secretary group
		$this->db->delete('group_permission', array('id_group' => "10", 'id_permission' => "2"));
		$this->db->delete('group_permission', array('id_group' => "10", 'id_permission' => "4"));

		// Adding the new permissions to the academic secretary group
		$this->db->insert_batch('group_permission', array(
			array(
				'id_group' => 11,
				'id_permission' => 15
			),
			array(
				'id_group' => 11,
				'id_permission' => 16
			),
			array(
				'id_group' => 11,
				'id_permission' => 17
			),
			array(
				'id_group' => 11,
				'id_permission' => 18
			),
			array(
				'id_group' => 11,
				'id_permission' => 19
			),
			array(
				'id_group' => 11,
				'id_permission' => 20
			)
		));
	}

	public function down(){
		
	}
}
