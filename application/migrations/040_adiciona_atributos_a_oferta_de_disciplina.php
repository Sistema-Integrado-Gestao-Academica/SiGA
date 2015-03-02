<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Adiciona_atributos_a_oferta_de_disciplina extends CI_Migration {

	public function up() {

		$fields = array(
			'class' => array('type' => "varchar(3)", 'default' => "A"),
			'total_vacancies' => array('type' => 'INT'),
			'current_vacancies' => array('type' => 'INT'),
			'main_teacher' => array('type' => 'INT'),
			'secondary_teacher' => array('type' => 'INT', 'null' => TRUE),

			// MODULARIZAR HORARIO, CRIAR TABELA
			'id_schedule' => array('type' => 'INT')
		);

		$this->dbforge->add_column('offer_discipline', $fields);

		$class_uk = "ALTER TABLE offer_discipline ADD CONSTRAINT CLASS_UK UNIQUE (id_offer, id_discipline, class)";
		$this->db->query($class_uk);

		$vacancy_ck = "ALTER TABLE offer_discipline ADD CONSTRAINT TOTAL_VACANCIES_CK CHECK (total_vacancies >= 0)";
		$this->db->query($vacancy_ck);

		$vacancy_ck = "ALTER TABLE offer_discipline ADD CONSTRAINT CURRENT_VACANCIES_CK CHECK (current_vacancies >= 0)";
		$this->db->query($vacancy_ck);

		$teacher_fk = "ALTER TABLE offer_discipline ADD CONSTRAINT IDMAINTEACHER_OFFERDISCIPLINE_FK FOREIGN KEY (main_teacher) REFERENCES users(id)";
		$this->db->query($teacher_fk);

		$teacher_fk = "ALTER TABLE offer_discipline ADD CONSTRAINT IDSECONDTEACHER_OFFERDISCIPLINE_FK FOREIGN KEY (secondary_teacher) REFERENCES users(id)";
		$this->db->query($teacher_fk);
	}

	public function down() {
		
		$fields = array(
			'class',
			'total_vacancies',
			'current_vacancies',
			'main_teacher',
			'secondary_teacher',
			'id_schedule'
		);

		foreach($fields as $column){
			
			$this->dbforge->drop_column('offer_discipline', $column);
		}

	}

}
