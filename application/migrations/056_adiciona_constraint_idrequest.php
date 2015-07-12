<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_adiciona_constraint_idrequest extends CI_Migration {

	public function up() {

		$fk = "ALTER TABLE request_discipline ADD CONSTRAINT IDREQUEST_FK FOREIGN KEY (id_request) REFERENCES student_request(id_request)";
		$this->db->query($fk);
	}

	public function down() {
		
		$fk = "ALTER TABLE request_discipline DROP FOREIGN KEY IDREQUEST_FK";
		$this->db->query($fk);	
	}

}