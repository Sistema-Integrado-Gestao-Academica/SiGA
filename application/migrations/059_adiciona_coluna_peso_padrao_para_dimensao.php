<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_adiciona_coluna_peso_padrao_para_dimensao extends CI_Migration {

	public function up() {

		$this->dbforge->add_column('dimension_type', array(
			'default_weight' => array('type' => 'DOUBLE')
		));
		

		$this->dbforge->modify_column('evaluation_dimension', array(
			'weight' => array('type' => 'DOUBLE')
		));
		
		/* Adding the standard weight values for dimensions types */
		$dimensionName = array(
			0 => "Proposta do Programa",
		 	1 => "Corpo Docente",
		 	2 => "Corpo Discente",
		 	3 => "Produção Intelectual",
		 	4 => "Inserção Social"
		 );

		define("STANDARD_WEIGHT_DIMENSION_1", 0);
		define("STANDARD_WEIGHT_DIMENSION_2", 20);
		define("STANDARD_WEIGHT_DIMENSION_3", 25);
		define("STANDARD_WEIGHT_DIMENSION_4", 35);
		define("STANDARD_WEIGHT_DIMENSION_5", 20);

		$this->db->where('dimension_type_name', $dimensionName[0]);
		$this->db->update('dimension_type', array('default_weight' => STANDARD_WEIGHT_DIMENSION_1));

		$this->db->where('dimension_type_name', $dimensionName[1]);
		$this->db->update('dimension_type', array('default_weight' => STANDARD_WEIGHT_DIMENSION_2));

		$this->db->where('dimension_type_name', $dimensionName[2]);
		$this->db->update('dimension_type', array('default_weight' => STANDARD_WEIGHT_DIMENSION_3));

		$this->db->where('dimension_type_name', $dimensionName[3]);
		$this->db->update('dimension_type', array('default_weight' => STANDARD_WEIGHT_DIMENSION_4));

		$this->db->where('dimension_type_name', $dimensionName[4]);
		$this->db->update('dimension_type', array('default_weight' => STANDARD_WEIGHT_DIMENSION_5));
		/**/
	}

	public function down() {

		$this->db->drop_column('dimension_type', 'default_weight');
	}
}
