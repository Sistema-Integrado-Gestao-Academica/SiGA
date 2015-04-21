<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Cria_tabela_class_hour_e_discipline_schedule extends CI_Migration {

	public function up() {

		$this->dbforge->drop_column('offer_discipline', 'id_schedule');

		// Creating class_hour table
		$this->dbforge->add_field(array(
			'id_class_hour' => array('type' => 'INT', 'auto_increment' => TRUE),
			'hour' => array('type' => 'INT'),
			'day' => array('type' => 'INT')
		));
		$this->dbforge->add_key('id_class_hour', TRUE);
		$this->dbforge->create_table('class_hour', TRUE);

		$this->dbforge->add_field(array(
			'id_offer_discipline' => array('type' => 'INT'),
			'id_class_hour' => array('type' => 'INT'),
			'class_local' => array('type' => 'varchar(15)', "null" => TRUE)
		));
		$this->dbforge->create_table('discipline_schedule', TRUE);

		$offer_discipline_fk = "ALTER TABLE discipline_schedule ADD CONSTRAINT IDOFFERDISCIPLINE_SCHEDULE_FK FOREIGN KEY (id_offer_discipline) REFERENCES offer_discipline(id_offer_discipline)";
		$this->db->query($offer_discipline_fk);

		$class_hour_fk = "ALTER TABLE discipline_schedule ADD CONSTRAINT IDCLASSHOUR_SCHEDULE_FK FOREIGN KEY (id_class_hour) REFERENCES class_hour(id_class_hour)";
		$this->db->query($class_hour_fk);

		$uk = "ALTER TABLE discipline_schedule ADD CONSTRAINT SCHEDULE_UK UNIQUE (id_offer_discipline, id_class_hour, class_local)";
		$this->db->query($uk);
		
	}

	public function down() {

		$offer_discipline_fk = "ALTER TABLE discipline_schedule DROP FOREIGN KEY IDOFFERDISCIPLINE_SCHEDULE_FK";
		$this->db->query($offer_discipline_fk);

		$class_hour_fk = "ALTER TABLE discipline_schedule DROP FOREIGN KEY IDCLASSHOUR_SCHEDULE_FK";
		$this->db->query($class_hour_fk);

		$uk = "ALTER TABLE discipline_schedule DROP INDEX SCHEDULE_UK";
		$this->db->query($uk);

		$this->dbforge->drop_table('discipline_schedule');
		$this->dbforge->drop_table('class_hour');
	}

}
