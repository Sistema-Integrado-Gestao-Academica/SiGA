<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_adiciona_constraint_unique_tabela_solicitacao extends CI_Migration {

	public function up() {

		$uk = "ALTER TABLE temporary_student_request ADD CONSTRAINT TEMP_REQUEST_UK UNIQUE (id_student, id_course, id_semester, discipline_class)";
		$this->db->query($uk);
	}

	public function down() {

		$uk = "ALTER TABLE temporary_student_request DROP INDEX TEMP_REQUEST_UK";
		$this->db->query($uk);
	}
}
