<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Adiciona_constraints_relacionadas_a_curso extends CI_Migration {

	public function up() {
		
		$addConstraint = "ALTER TABLE offer DROP FOREIGN KEY IDCOURSE_OFFER_FK";
		$this->db->query($addConstraint);
		$addConstraint = "ALTER TABLE offer ADD CONSTRAINT IDCOURSE_OFFER_FK FOREIGN KEY (course) REFERENCES course(id_course) ON DELETE CASCADE ON UPDATE RESTRICT";
		$this->db->query($addConstraint);
		
		$addConstraint = "ALTER TABLE offer_discipline DROP FOREIGN KEY IDOFFER_OFFERDISCIPLINE_FK";
		$this->db->query($addConstraint);
		$addConstraint = "ALTER TABLE offer_discipline ADD CONSTRAINT IDOFFER_OFFERDISCIPLINE_FK FOREIGN KEY (id_offer) REFERENCES offer(id_offer) ON DELETE CASCADE ON UPDATE RESTRICT";
		$this->db->query($addConstraint);

		$addConstraint = "ALTER TABLE offer_discipline DROP FOREIGN KEY IDDISCIPLINE_OFFERDISCIPLINE_FK";
		$this->db->query($addConstraint);
		$addConstraint = "ALTER TABLE offer_discipline ADD CONSTRAINT IDDISCIPLINE_OFFERDISCIPLINE_FK FOREIGN KEY (id_discipline) REFERENCES discipline(discipline_code) ON DELETE CASCADE ON UPDATE RESTRICT";
		$this->db->query($addConstraint);

		$addConstraint = "ALTER TABLE program_course DROP FOREIGN KEY PROGRAMCOURSE_IDPROGRAM_FK";
		$this->db->query($addConstraint);
		$addConstraint = "ALTER TABLE program_course ADD CONSTRAINT PROGRAMCOURSE_IDPROGRAM_FK FOREIGN KEY (id_program) REFERENCES program(id_program) ON DELETE CASCADE ON UPDATE RESTRICT";
		$this->db->query($addConstraint);

		$addConstraint = "ALTER TABLE program_course DROP FOREIGN KEY PROGRAMCOURSE_IDCOURSE_FK";
		$this->db->query($addConstraint);
		$addConstraint = "ALTER TABLE program_course ADD CONSTRAINT PROGRAMCOURSE_IDCOURSE_FK FOREIGN KEY (id_course) REFERENCES course(id_course) ON DELETE CASCADE ON UPDATE RESTRICT";
		$this->db->query($addConstraint);

		$addConstraint = "ALTER TABLE course_syllabus DROP FOREIGN KEY IDCOURSE_SYLLABUS_FK";
		$this->db->query($addConstraint);
		$addConstraint = "ALTER TABLE course_syllabus ADD CONSTRAINT IDCOURSE_SYLLABUS_FK FOREIGN KEY (id_course) REFERENCES course(id_course) ON DELETE CASCADE ON UPDATE RESTRICT";
		$this->db->query($addConstraint);

		$addConstraint = "ALTER TABLE syllabus_discipline DROP FOREIGN KEY IDSYLLABUS_SYLLABUSDISCIPLINE_FK";
		$this->db->query($addConstraint);
		$addConstraint = "ALTER TABLE syllabus_discipline ADD CONSTRAINT IDSYLLABUS_SYLLABUSDISCIPLINE_FK FOREIGN KEY (id_syllabus) REFERENCES course_syllabus(id_syllabus) ON DELETE CASCADE ON UPDATE RESTRICT";
		$this->db->query($addConstraint);

		$addConstraint = "ALTER TABLE syllabus_discipline DROP FOREIGN KEY IDDISCIPLINE_SYLLABUSDISCIPLINE_FK";
		$this->db->query($addConstraint);
		$addConstraint = "ALTER TABLE syllabus_discipline ADD CONSTRAINT IDDISCIPLINE_SYLLABUSDISCIPLINE_FK FOREIGN KEY (id_discipline) REFERENCES discipline(discipline_code) ON DELETE CASCADE ON UPDATE RESTRICT";
		$this->db->query($addConstraint);
	}

	public function down() {
		
		$addConstraint = "ALTER TABLE offer DROP FOREIGN KEY IDCOURSE_OFFER_FK";
		$this->db->query($addConstraint);
		$addConstraint = "ALTER TABLE offer ADD CONSTRAINT IDCOURSE_OFFER_FK FOREIGN KEY (course) REFERENCES course(id_course) ON DELETE RESTRICT ON UPDATE RESTRICT";
		$this->db->query($addConstraint);
		
		$addConstraint = "ALTER TABLE offer_discipline DROP FOREIGN KEY IDOFFER_OFFERDISCIPLINE_FK";
		$this->db->query($addConstraint);
		$addConstraint = "ALTER TABLE offer_discipline ADD CONSTRAINT IDOFFER_OFFERDISCIPLINE_FK FOREIGN KEY (id_offer) REFERENCES offer(id_offer) ON DELETE RESTRICT ON UPDATE RESTRICT";
		$this->db->query($addConstraint);

		$addConstraint = "ALTER TABLE offer_discipline DROP FOREIGN KEY IDDISCIPLINE_OFFERDISCIPLINE_FK";
		$this->db->query($addConstraint);
		$addConstraint = "ALTER TABLE offer_discipline ADD CONSTRAINT IDDISCIPLINE_OFFERDISCIPLINE_FK FOREIGN KEY (id_discipline) REFERENCES discipline(discipline_code) ON DELETE RESTRICT ON UPDATE RESTRICT";
		$this->db->query($addConstraint);

		$addConstraint = "ALTER TABLE program_course DROP FOREIGN KEY PROGRAMCOURSE_IDPROGRAM_FK";
		$this->db->query($addConstraint);
		$addConstraint = "ALTER TABLE program_course ADD CONSTRAINT PROGRAMCOURSE_IDPROGRAM_FK FOREIGN KEY (id_program) REFERENCES program(id_program) ON DELETE RESTRICT ON UPDATE RESTRICT";
		$this->db->query($addConstraint);

		$addConstraint = "ALTER TABLE program_course DROP FOREIGN KEY PROGRAMCOURSE_IDCOURSE_FK";
		$this->db->query($addConstraint);
		$addConstraint = "ALTER TABLE program_course ADD CONSTRAINT PROGRAMCOURSE_IDCOURSE_FK FOREIGN KEY (id_course) REFERENCES course(id_course) ON DELETE RESTRICT ON UPDATE RESTRICT";
		$this->db->query($addConstraint);

		$addConstraint = "ALTER TABLE course_syllabus DROP FOREIGN KEY IDCOURSE_SYLLABUS_FK";
		$this->db->query($addConstraint);
		$addConstraint = "ALTER TABLE course_syllabus ADD CONSTRAINT IDCOURSE_SYLLABUS_FK FOREIGN KEY (id_course) REFERENCES course(id_course) ON DELETE RESTRICT ON UPDATE RESTRICT";
		$this->db->query($addConstraint);

		$addConstraint = "ALTER TABLE syllabus_discipline DROP FOREIGN KEY IDSYLLABUS_SYLLABUSDISCIPLINE_FK";
		$this->db->query($addConstraint);
		$addConstraint = "ALTER TABLE syllabus_discipline ADD CONSTRAINT IDSYLLABUS_SYLLABUSDISCIPLINE_FK FOREIGN KEY (id_syllabus) REFERENCES course_syllabus(id_syllabus) ON DELETE RESTRICT ON UPDATE RESTRICT";
		$this->db->query($addConstraint);

		$addConstraint = "ALTER TABLE syllabus_discipline DROP FOREIGN KEY IDDISCIPLINE_SYLLABUSDISCIPLINE_FK";
		$this->db->query($addConstraint);
		$addConstraint = "ALTER TABLE syllabus_discipline ADD CONSTRAINT IDDISCIPLINE_SYLLABUSDISCIPLINE_FK FOREIGN KEY (id_discipline) REFERENCES discipline(discipline_code) ON DELETE RESTRICT ON UPDATE RESTRICT";
		$this->db->query($addConstraint);
	}
}
