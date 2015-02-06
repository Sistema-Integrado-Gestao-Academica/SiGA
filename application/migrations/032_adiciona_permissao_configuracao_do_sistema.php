<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Adiciona_permissao_configuracao_do_sistema extends CI_Migration {

	public function up() {
		$object = array(
			'id_permission' => 11,
			'permission_name' => 'Configurações',
			'route' => 'configuracoes'
		);
		$this->db->insert('permission', $object);

		$object = array(
			'id_group' => 3,
			'id_permission' => 11
		);
		$this->db->insert('group_permission', $object);
	}

	public function down() {
		$object = array(
			'id_group' => 3,
			'id_permission' => 11
		);
		$this->db->delete('group_permission', $object);

		$object = array(
			'permission_name' => 'Configurações',
			'route' => 'configuracoes'
		);
		$this->db->delete('permission', $object);
	}

}

/* End of file 032_adiciona_permissao_configuracao_do_sistema.php */
/* Location: ./application/migrations/032_adiciona_permissao_configuracao_do_sistema.php */