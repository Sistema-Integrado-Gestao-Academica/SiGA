<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_aumenta_tamanho_notificacao extends CI_Migration {

	public function up() {

		$this->dbforge->modify_column('notification', array(
			'content' => array('type'=> "TEXT")
		));
	}

	public function down(){
		
	}
}