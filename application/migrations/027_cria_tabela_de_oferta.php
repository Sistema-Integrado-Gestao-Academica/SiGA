<?php
class Migration_Cria_tabela_de_oferta extends CI_migration {

	public function up() {
		
		// Offer table
		$this->dbforge->add_field(array(
			'id_offer' => array('type' => 'INT', 'auto_increment' => TRUE),
			'offer_status' => array('type' => 'VARCHAR(8)', 'default' => 'proposed')
		));
		$this->dbforge->add_key('id_offer', TRUE);
		$this->dbforge->create_table('offer');

	}

	public function down() {
		$this->dbforge->drop_table('offer');
	}
}
