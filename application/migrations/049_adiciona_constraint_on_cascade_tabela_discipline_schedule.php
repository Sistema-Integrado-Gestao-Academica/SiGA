<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_adiciona_constraint_on_cascade_tabela_discipline_schedule extends CI_Migration {

	public function up() {

		$drop_fk = "ALTER TABLE discipline_schedule DROP FOREIGN KEY IDOFFERDISCIPLINE_SCHEDULE_FK";
		$this->db->query($drop_fk);

		$fk = "ALTER TABLE discipline_schedule ADD CONSTRAINT IDOFFERDISCIPLINE_SCHEDULE_FK FOREIGN KEY (id_offer_discipline) REFERENCES offer_discipline(id_offer_discipline) ON DELETE CASCADE ON UPDATE RESTRICT";
		$this->db->query($fk);
	}

	public function down() {

		$drop_fk = "ALTER TABLE discipline_schedule DROP FOREIGN KEY IDOFFERDISCIPLINE_SCHEDULE_FK";
		$this->db->query($drop_fk);

		$fk = "ALTER TABLE discipline_schedule ADD CONSTRAINT IDOFFERDISCIPLINE_SCHEDULE_FK FOREIGN KEY (id_offer_discipline) REFERENCES offer_discipline(id_offer_discipline) ON DELETE RESTRICT ON UPDATE RESTRICT";
		$this->db->query($fk);
	}
}
