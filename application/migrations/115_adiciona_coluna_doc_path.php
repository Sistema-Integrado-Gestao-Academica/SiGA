<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Adiciona_coluna_doc_path extends CI_Migration {

	public function up() {

		$this->dbforge->add_column('document_request', array(
			'doc_path' => array('type' => 'TEXT', "null" => TRUE, "default" => NULL)
		));

		$this->dbforge->modify_column('notification', array(
			'type' => array('type'=> "VARCHAR(50)"),
			'content' => array('type'=> "TEXT")
		));
	}

	public function down(){
		$this->dbforge->add_column('document_request', "doc_path");
	}
}