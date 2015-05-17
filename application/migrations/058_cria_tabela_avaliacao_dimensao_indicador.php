<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Cria_tabela_avaliacao_dimensao_indicador extends CI_Migration {

	public function up() {

		// Creating program evaluation table
		$this->dbforge->add_field(array(
			'id_program_evaluation' => array('type' => 'INT', 'auto_increment' => true),
			'id_program' => array('type' => 'INT'),
			'current_year' => array('type' => 'YEAR'),
			'start_year' => array('type' => 'YEAR'),
			'end_year' => array('type' => 'YEAR'),
			'general_note' => array('type' => "INT")
		));
		$this->dbforge->add_key('id_program_evaluation', true);
		$this->dbforge->create_table('program_evaluation', true);

		$fk = "ALTER TABLE program_evaluation ADD CONSTRAINT IDPROGRAM_PROGRAMEVALUATION_FK FOREIGN KEY (id_program)
		 REFERENCES program(id_program)";
		$this->db->query($fk);

		// Creating dimension type table
		$this->dbforge->add_field(array(
			'id_dimension_type' => array('type' => 'INT', 'auto_increment' => true),
			'dimension_type_name' => array('type' => 'varchar(30)')
		));
		$this->dbforge->add_key('id_dimension_type', true);
		$this->dbforge->create_table('dimension_type', true);

		$uk = "ALTER TABLE dimension_type ADD CONSTRAINT DIMENSION_TYPE_NAME_UK UNIQUE(dimension_type_name)";
		$this->db->query($uk);

			// Adding the 5 standard dimensions to the database
			$dimensions = array("Proposta do Programa", "Corpo Docente", "Corpo Discente", "Produção Intelectual", "Inserção Social");
			foreach ($dimensions as $dimension_name){
				$this->db->insert('dimension_type', array('dimension_type_name' => $dimension_name));
			}

		// Creating the table that relates the dimension with the evaluation
		$this->dbforge->add_field(array(
			'id_dimension' => array('type' => 'INT', 'auto_increment' => true),
			'id_evaluation' => array('type' => 'INT'),
			'id_dimension_type' => array('type' => 'INT'),
			'weight' => array('type' => "INT"),
			'indicators_note' => array('type' => 'INT')
		));
		$this->dbforge->add_key('id_dimension', true);
		$this->dbforge->create_table('evaluation_dimension', true);

		$fk = "ALTER TABLE evaluation_dimension ADD CONSTRAINT IDEVALUATION_DIMENSION_FK FOREIGN KEY (id_evaluation)
		 REFERENCES program_evaluation(id_program_evaluation)";
		$this->db->query($fk);

		$fk = "ALTER TABLE evaluation_dimension ADD CONSTRAINT IDDIMENSIONTYPE_DIMENSION_FK FOREIGN KEY (id_dimension_type)
		 REFERENCES dimension_type(id_dimension_type)";
		$this->db->query($fk);

		$uk = "ALTER TABLE evaluation_dimension ADD CONSTRAINT DIMENSIONTYPE_EVALUATION_UK UNIQUE(id_evaluation, id_dimension_type)";
		$this->db->query($uk);		
	}

	public function down(){
		
		
	}
}
