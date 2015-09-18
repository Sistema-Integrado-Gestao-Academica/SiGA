<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_cria_tabela_solicitacao_documentacao extends CI_Migration {

	public function up() {

		// Create document_type table
		$this->dbforge->add_field(array(
			'id_type' => array('type' => 'INT', 'auto_increment' => TRUE),
			'document_type' => array('type' => 'varchar(50)')
		));
		$this->dbforge->add_key('id_type', TRUE);
		$this->dbforge->create_table('document_type', TRUE);

		$this->populateDocumentType();

		// Create table document_request
		$this->dbforge->add_field(array(
			'id_request' => array('type' => 'INT', 'auto_increment' => TRUE),
			'id_student' => array('type' => 'INT'),
			'id_course' => array('type' => 'INT'),
			'document_type' => array('type' => 'INT'),
			'status' => array('type' => 'varchar(20)'),
			'other_name' => array('type' => 'varchar(50)', "null" => TRUE)
		));
		$this->dbforge->add_key('id_request', TRUE);
		$this->dbforge->create_table('document_request', TRUE);

		$addConstraint = "ALTER TABLE document_request ADD CONSTRAINT STUDENT_DOCUMENTREQUEST_FK FOREIGN KEY (id_student) REFERENCES users(id)";
		$this->db->query($addConstraint);

		$addConstraint = "ALTER TABLE document_request ADD CONSTRAINT COURSE_DOCUMENTREQUEST_FK FOREIGN KEY (id_course) REFERENCES course(id_course)";
		$this->db->query($addConstraint);

		$addConstraint = "ALTER TABLE document_request ADD CONSTRAINT DOCTYPE_DOCUMENTREQUEST_FK FOREIGN KEY (document_type) REFERENCES document_type(id_type)";
		$this->db->query($addConstraint);
	}

	private function populateDocumentType(){

		$types = array(
			"Solicitação de Banca de Qualificação",
			"Solicitação de Banca de Defesa",
			"Solicitação de Passagem",
			"Histórico Escolar",
			"Documentos para Transferência",
			"Grade Horária",
			"Outro"
		);

		foreach ($types as $key => $type){
			$this->db->insert('document_type', array('document_type' => $type));
		}
	}

	public function down(){
				
	}
}
