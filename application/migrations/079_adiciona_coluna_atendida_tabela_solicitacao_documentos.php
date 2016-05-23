<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_adiciona_coluna_atendida_tabela_solicitacao_documentos extends CI_Migration {

	public function up() {

		$this->dbforge->add_column('document_request', array(
			'answered' => array('type' => "TINYINT", "default" => 0)
		));	
	}

	public function down() {
		
		$this->dbforge->drop_column('document_request', 'answered');
	}

}