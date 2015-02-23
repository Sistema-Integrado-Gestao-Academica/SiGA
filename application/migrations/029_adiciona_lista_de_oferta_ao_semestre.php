<?php
class Migration_Adiciona_lista_de_oferta_ao_semestre extends CI_migration {

	public function up() {
		
		$offerColumn = array(
			'offer' => array('type' => 'INT', 'null' => TRUE)
		);
		$this->dbforge->add_column('semester', $offerColumn);

		$offer_fk = "ALTER TABLE semester ADD CONSTRAINT IDOFFER_SEMESTER_FK FOREIGN KEY (offer) REFERENCES offer (id_offer) ON DELETE SET NULL";
		$this->db->query($offer_fk);

		$offer_uk = "ALTER TABLE semester ADD CONSTRAINT IDOFFER_SEMESTER_UK UNIQUE (offer)";
		$this->db->query($offer_uk);

	}

	public function down() {

		$offer_fk = "ALTER TABLE semester DROP CONSTRAINT IDOFFER_SEMESTER_FK";
		$this->db->query($offer_fk);

		$this->dbforge->drop_column('semester', 'offer');
	}
}
