<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_preenche_tabela_class_hour extends CI_Migration {

	public function up() {

		$uk = "ALTER TABLE class_hour ADD CONSTRAINT DAY_HOUR_UK UNIQUE (hour, day)";
		$this->db->query($uk);

		for($i = 1; $i <= 9; $i++){
			for($j = 1; $j <= 6; $j++){
				$this->db->insert('class_hour', array('hour' => $i, 'day' => $j));
			}
		}
	}

	public function down() {

		$uk = "ALTER TABLE class_hour DROP INDEX DAY_HOUR_UK";
		$this->db->query($uk);

		$this->db->truncate('class_hour');
	}

}
