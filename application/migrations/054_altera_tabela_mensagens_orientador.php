<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Altera_tabela_mensagens_orientador extends CI_Migration {

	public function up() {

		$addConstraint = "ALTER TABLE mastermind_message ADD id_offer_discipline INT NULL DEFAULT NULL AFTER id_request, ADD INDEX (id_offer_discipline) ";
		$this->db->query($addConstraint);
		
		$addConstraint = "ALTER TABLE mastermind_message ADD FOREIGN KEY (id_offer_discipline) REFERENCES offer_discipline(id_offer_discipline) ON DELETE CASCADE ON UPDATE RESTRICT;";
		$this->db->query($addConstraint);
	}

	public function down(){
		
		$this->dbforge->drop_table('mastermind_message');
		
		$dropConstraint = "ALTER TABLE mastermind_message DROP FOREIGN KEY IDOFFERDISCIPLINE_FK";
		$this->db->query($dropConstraint);
		
		$dropConstraint = "ALTER TABLE mastermind_message DROP column id_offer_discipline";
		$this->db->query($dropConstraint);
		
	}
}
