<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_adiciona_coluna_peso_padrao_para_dimensao extends CI_Migration {

	public function up() {

		$this->dbforge->add_column('dimension_type', array(
			'default_weight' => array('type' => 'DOUBLE')
		));
		
		$this->db->update('dimension_type', array('default_weight' => 20));

	}

	public function down() {

		$this->db->drop_column('dimension_type', 'default_weight');
	}
}
