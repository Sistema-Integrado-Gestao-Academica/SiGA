<?php
class Migration_Cria_tabela_secretaria_de_cursos extends CI_migration {

	public function up() {

		$this->dbforge->add_field(array(
				'id_secretary'  => array('type' => 'INT', 'auto_increment' => TRUE),
				'id_user' => array('type' => 'INT'),
				'id_course' => array('type' => 'INT'),
				'id_group' => array('type' => 'INT')
		));
		$this->dbforge->add_key('id_secretary', true);
		$this->dbforge->create_table('secretary_course', true);

		$add_iduser_fk = "ALTER TABLE secretary_course ADD CONSTRAINT IDUSER_SECRETARYCOURSE_FK FOREIGN KEY (id_user) REFERENCES users(id) ON DELETE CASCADE ON UPDATE RESTRICT";
		$this->db->query($add_iduser_fk);

		$add_idcourse_fk = "ALTER TABLE secretary_course ADD CONSTRAINT IDCOURSE_SECRETARYCOURSE_FK FOREIGN KEY (id_course) REFERENCES course(id_course) ON DELETE CASCADE ON UPDATE RESTRICT";
		$this->db->query($add_idcourse_fk);
		
		// $add_idgroup_fk = "ALTER TABLE secretary_course ADD CONSTRAINT IDGROUP_SECRETARYCOURSE_FK FOREIGN KEY (id_group) REFERENCES group(id_group) ON DELETE CASCADE ON UPDATE RESTRICT";
		// $this->db->query($add_idgroup_fk);

	}

	public function down() {
		$this->dbforge->drop_table('secretary_course');
	}
}
