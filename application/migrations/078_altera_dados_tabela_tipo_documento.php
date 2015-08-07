<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_altera_dados_tabela_tipo_documento extends CI_Migration {

	public function up() {

		// Changing the size of the document_type column
		$this->dbforge->modify_column('document_type', array(
			'document_type' => array('type' => 'varchar(60)')
		));

		// Create declaration column to tell apart the declararion types from the others
		$this->dbforge->add_column('document_type', array(
			'declaration' => array('type' => 'TINYINT', 'default' => 0)
		));

		// Disable constraint
		$disableConstraint = "SET FOREIGN_KEY_CHECKS = 0";
		$this->db->query($disableConstraint);
		
		// Delete all data from the table
		$this->db->truncate('document_type');

		// Populate table with new data
		$this->populateDocumentType();

		// Enable constraint back
		$enableConstraint = "SET FOREIGN_KEY_CHECKS = 1";
		$this->db->query($enableConstraint);
	}

	private function populateDocumentType(){

		$types = array(
			1 => "Solicitação de Banca de Qualificação",
			2 => "Solicitação de Banca de Defesa",
			3 => "Solicitação de Passagem",
			4 => "Documentos para Transferência",
			5 => "Declarações",
		);

		foreach ($types as $id => $type){
			$this->db->insert('document_type', array('id_type' => $id, 'document_type' => $type));
		}

		$declarationTypes = array(
			6 => "Declaração de Aluno Regular",
			7 => "Declaração de Trancamento Geral de Matrícula",
			8 => "Declaração de Provável Formando",
			9 => "Declaração de Matrícula em Disciplina",
			10 => "Declaração de Grade Horária",
			11 => "Declaração de Formado",
			12 => "Declaração de Conduta Acadêmica: Aluno Regular",
			13 => "Declaração de Conduta Acadêmica: Ex-aluno",
			14 => "Declaração de Monitoria",
			15 => "Declaração de Período do Curso",
			16 => "Declaração de Disciplinas Cursadas: Aluno Especial",
			17 => "Declaração de Formado no Período"
		);

		foreach ($declarationTypes as $id => $type){
			$this->db->insert('document_type', array('id_type' => $id, 'document_type' => $type, 'declaration' => 1));
		}		
		
		// Add "Outro" type
		$this->db->insert('document_type', array('id_type' => 18, 'document_type' => "Outro"));
	}

	public function down(){
				
	}
}
