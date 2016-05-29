<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Adiciona_coluna_doc_path extends CI_Migration {

	public function up() {

		$this->dbforge->add_column('document_request', array(
			'doc_path' => array('type' => 'TEXT', "null" => TRUE, "default" => NULL)
		));
	}

	public function down(){
		$this->dbforge->add_column('document_request', "doc_path");
	}
}