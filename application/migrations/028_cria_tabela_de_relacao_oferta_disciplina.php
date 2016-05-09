<?php
class Migration_Cria_tabela_de_relacao_oferta_disciplina extends CI_migration {

	public function up() {
		
		// Offer and discipline relation table
		$this->dbforge->add_field(array(
			'id_offer' => array('type' => 'INT'),
			'id_discipline' => array('type' => 'INT')
		));
		$this->dbforge->create_table('offer_discipline');

		$offer_fk = "ALTER TABLE offer_discipline ADD CONSTRAINT IDOFFER_OFFERDISCIPLINE_FK FOREIGN KEY (id_offer) REFERENCES offer (id_offer)";
		$this->db->query($offer_fk);

		$discipline_fk = "ALTER TABLE offer_discipline ADD CONSTRAINT IDDISCIPLINE_OFFERDISCIPLINE_FK FOREIGN KEY (id_discipline) REFERENCES discipline (discipline_code)";
		$this->db->query($discipline_fk);
	}

	public function down() {

		$offer_fk = "ALTER TABLE offer_discipline DROP CONSTRAINT IDOFFER_OFFERDISCIPLINE_FK";
		$this->db->query($offer_fk);

		$discipline_fk = "ALTER TABLE offer_discipline DROP CONSTRAINT IDDISCIPLINE_OFFERDISCIPLINE_FK";
		$this->db->query($discipline_fk);

		$this->dbforge->drop_table('offer_discipline');
	}
}
