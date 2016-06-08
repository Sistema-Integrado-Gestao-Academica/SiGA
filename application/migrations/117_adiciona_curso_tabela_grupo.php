<?php
class Migration_Adiciona_curso_tabela_grupo extends CI_migration {

	public function up() {

		/* Creating column of course for the trainee groups 
		that will be created */
		$this->dbforge->add_column('group', array(
			'id_secretary' => array('type' => "int")
		));
		
		$id_secretary_fk = "ALTER TABLE group ADD CONSTRAINT SECRETARY_ID_GROUP_FK FOREIGN KEY (id_secretary) REFERENCES users(id)";
		$this->db->query($id_secretary_fk);

		// // Creating table course trainee relation
		$this->dbforge->add_field(array(
			'id_trainee' => array('type' => 'INT'),
			'id_course' => array('type' => 'INT')
		));
		$this->dbforge->create_table('course_trainee', true);

		$id_course_fk = "ALTER TABLE course_trainee ADD CONSTRAINT IDCOURSE_TRAINEE_FK FOREIGN KEY (id_course) REFERENCES course(id_course)";
		$this->db->query($id_course_fk);

		$id_trainee_fk = "ALTER TABLE course_trainee ADD CONSTRAINT ID_TRAINEE_FK FOREIGN KEY (id_trainee) REFERENCES users(id)";
		$this->db->query($id_trainee_fk);
	}

	public function down() {
	}
}
