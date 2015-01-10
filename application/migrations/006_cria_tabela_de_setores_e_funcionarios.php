<?php 
class Migration_Cria_tabela_de_setores_e_funcionarios extends CI_migration {
	
	public function up() {
		$this->dbforge->add_field(array(
			'setor_id' => array('type' => 'INT'),
			'funcionarios_id' => array('type' => 'INT')
		));
		$this->dbforge->create_table('setores_funcionarios', true);

		// Adding the foreign keys constraints
		$add_foreign_key = "ALTER TABLE setores_funcionarios ADD CONSTRAINT IDSECTOR_SECTOREMPLOYEE FOREIGN KEY (setor_id) REFERENCES setores(id)";
		$this->db->query($add_foreign_key);
		
		$add_foreign_key = "ALTER TABLE setores_funcionarios ADD CONSTRAINT IDEMPLOYEE_SECTOREMPLOYEE FOREIGN KEY (funcionarios_id) REFERENCES funcionarios(id)";
		$this->db->query($add_foreign_key);
	}

	public function down() {
		$this->dbforge->drop_table('setores_funcionarios');
	}
}
